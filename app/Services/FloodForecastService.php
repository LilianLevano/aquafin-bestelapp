<?php

namespace App\Services;

use App\Models\FloodRiskAnalysis;
use App\Models\RiskMonth;
use Carbon\Carbon;

/**
 * Generates, persists, and retrieves multi-year flood risk predictions for a given site.
 *
 * Predictions are based on processed daily weather records from {@see RiskCalculationService}
 * and are extrapolated over a 5-year horizon using seasonal precipitation multipliers
 * derived from Belgian climate patterns.
 * Results are stored in the {@see FloodRiskAnalysis} and {@see RiskMonth} tables
 * via upsert (updateOrCreate) to avoid duplicate records across cache refreshes.
 */
class FloodForecastService
{
    /**
     * Minimum average risk score (0–100) at or above which a month is flagged as a risk month
     * and a {@see RiskMonth} record is created.
     */
    const RISK_THRESHOLD = 70;

    /**
     * Seasonal precipitation multipliers applied to base monthly data during prediction.
     *
     * Reflects Belgian climate patterns: higher precipitation in winter and autumn,
     * lower in summer. Applied to risk scores and precipitation totals alike.
     *
     * @var array<string, float>
     */
    const SEASONAL_MULTIPLIERS = [
        'winter' => 1.3,
        'spring' => 1.0,
        'summer' => 0.8,
        'autumn' => 1.2,
    ];

    /**
     * Generate a 5-year monthly flood risk prediction for the given site.
     *
     * Aggregates the provided daily records into monthly summaries via
     * {@see aggregateToMonthly()}, then projects each month forward across
     * 5 years by applying the seasonal multiplier for that month's season.
     * If no real data exists for a given month, {@see simulateBaseForMonth()}
     * is used to derive a baseline from Belgian climate averages.
     * A month is flagged as extreme if its projected max risk >= 90
     * or total precipitation >= 100 mm.
     *
     * @param array $dailyRecords Processed daily records from {@see RiskCalculationService::processDailyRecords()}.
     *                            Each entry must contain: 'date' (date string), 'riskValue' (float|null),
     *                            'rainMm' (float|null), and 'humidity' (float|null).
     * @param int   $siteId       Primary key of the site to generate predictions for.
     *
     * @return array Flat array of monthly prediction entries, each containing:
     *               'site_id', 'year', 'month', 'min_risk', 'max_risk', 'avg_risk',
     *               'total_precipitation', 'avg_humidity', 'season', 'is_extreme'.
     */
    public function generateFiveYearPrediction(array $dailyRecords, int $siteId): array
    {
        $baseMonthlyData = $this->aggregateToMonthly($dailyRecords);
        $predictions     = [];
        $currentYear     = now()->year;

        for ($year = $currentYear; $year < $currentYear + 5; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $season     = $this->getSeason($month);
                $multiplier = self::SEASONAL_MULTIPLIERS[$season];

                // Use base data if available, otherwise simulate from seasonal patterns
                $base = $baseMonthlyData[$month] ?? $this->simulateBaseForMonth($month);

                $minRisk   = round($base['min_risk'] * $multiplier, 2);
                $maxRisk   = round($base['max_risk'] * $multiplier, 2);
                $avgRisk   = round($base['avg_risk'] * $multiplier, 2);
                $precip    = round($base['total_precipitation'] * $multiplier, 2);
                $humidity  = round($base['avg_humidity'], 2);
                $isExtreme = $maxRisk >= 90 || $precip >= 100;

                $predictions[] = [
                    'site_id'              => $siteId,
                    'year'                 => $year,
                    'month'                => $month,
                    'min_risk'             => $minRisk,
                    'max_risk'             => $maxRisk,
                    'avg_risk'             => $avgRisk,
                    'total_precipitation'  => $precip,
                    'avg_humidity'         => $humidity,
                    'season'               => $season,
                    'is_extreme'           => $isExtreme,
                ];
            }
        }

        return $predictions;
    }

    /**
     * Persist prediction entries to the database and identify risk months.
     *
     * Each prediction is upserted into the {@see FloodRiskAnalysis} table using
     * (site_id, year, month) as the unique key, so re-running predictions for the
     * same site does not produce duplicate records.
     * If a prediction's avg_risk meets or exceeds {@see RISK_THRESHOLD}, a corresponding
     * {@see RiskMonth} record is also upserted with the computed risk reason.
     *
     * @param array $predictions Flat array of prediction entries as returned by
     *                           {@see generateFiveYearPrediction()}.
     * @param int   $siteId      Primary key of the site these predictions belong to.
     *
     * @return array{analyses: FloodRiskAnalysis[], riskMonths: RiskMonth[]}
     *              Associative array with 'analyses' (all upserted analysis records)
     *              and 'riskMonths' (only the records that exceeded the risk threshold).
     */
    public function savePredictions(array $predictions, int $siteId): array
    {
        $savedAnalyses = [];
        $savedRiskMonths = [];

        foreach ($predictions as $prediction) {
            // Upsert — update if exists for same site/year/month
            $analysis = FloodRiskAnalysis::updateOrCreate(
                [
                    'site_id' => $siteId,
                    'year'    => $prediction['year'],
                    'month'   => $prediction['month'],
                ],
                $prediction
            );

            $savedAnalyses[] = $analysis;

            // Identify and save risk months
            if ($analysis->avg_risk >= self::RISK_THRESHOLD) {
                $reason = $this->buildRiskReason($prediction);

                $riskMonth = RiskMonth::updateOrCreate(
                    [
                        'site_id'                => $siteId,
                        'year'                   => $prediction['year'],
                        'month'                  => $prediction['month'],
                    ],
                    [
                        'flood_risk_analysis_id' => $analysis->id,
                        'risk_value'             => $analysis->avg_risk,
                        'threshold'              => self::RISK_THRESHOLD,
                        'reason'                 => $reason,
                    ]
                );

                $savedRiskMonths[] = $riskMonth;
            }
        }

        return [
            'analyses'   => $savedAnalyses,
            'riskMonths' => $savedRiskMonths,
        ];
    }

    /**
     * Retrieve persisted predictions for a site from the database.
     *
     * Returns all {@see FloodRiskAnalysis} and {@see RiskMonth} records for the given site
     * from the current year onwards, sorted by year and month descending.
     * Returns null if no analyses exist for the site.
     *
     * Note: this method fetches all rows from both tables into memory before filtering.
     * For large datasets, consider replacing ::all()->whereIn() with a query builder
     * approach (e.g. FloodRiskAnalysis::where('site_id', $siteId)->where(...)->get()).
     *
     * @param int $siteId Primary key of the site to retrieve predictions for.
     *
     * @return array{analyses: array, riskMonths: array}|null
     *         Associative array with 'analyses' and 'riskMonths' as plain arrays,
     *         or null if no predictions exist for the given site.
     */
    public function getCachedPredictions(int $siteId): ?array
    {
        $analyses = FloodRiskAnalysis::all()->whereIn('site_id', [$siteId])
            ->where('year', '>=', now()->year)
            ->sortByDesc('year')
            ->sortByDesc('month');

        if ($analyses->isEmpty()) {
            return null;
        }

        $riskMonths = RiskMonth::all()->whereIn('site_id', [$siteId])
            ->where('year', '>=', now()->year)
            ->sortByDesc('year')
            ->sortByDesc('month');

        return [
            'analyses'   => $analyses->toArray(),
            'riskMonths' => $riskMonths->toArray(),
        ];
    }

    /**
     * Aggregate daily weather records into per-month summaries.
     *
     * Groups records by calendar month (1–12), collecting risk values,
     * precipitation totals, and humidity values. Null values in any field
     * are silently skipped. The resulting summary per month contains:
     * 'min_risk', 'max_risk', 'avg_risk', 'total_precipitation', 'avg_humidity'.
     *
     * @param array $dailyRecords Array of daily records, each containing:
     *                            'date' (date string parseable by Carbon),
     *                            'riskValue' (float|null),
     *                            'rainMm' (float|null),
     *                            'humidity' (float|null).
     *
     * @return array<int, array> Monthly summaries keyed by month number (1–12).
     */
    private function aggregateToMonthly(array $dailyRecords): array
    {
        $monthly = [];

        foreach ($dailyRecords as $day) {
            $month = (int) Carbon::parse($day['date'])->format('m');

            if (!isset($monthly[$month])) {
                $monthly[$month] = [
                    'risk_values'         => [],
                    'total_precipitation' => 0,
                    'humidity_values'     => [],
                ];
            }

            if ($day['riskValue'] !== null) {
                $monthly[$month]['risk_values'][] = $day['riskValue'];
            }
            if ($day['rainMm'] !== null) {
                $monthly[$month]['total_precipitation'] += $day['rainMm'];
            }
            if ($day['humidity'] !== null) {
                $monthly[$month]['humidity_values'][] = $day['humidity'];
            }
        }

        $result = [];
        foreach ($monthly as $month => $data) {
            $risks = $data['risk_values'];
            $humidities = $data['humidity_values'];

            $result[$month] = [
                'min_risk'            => !empty($risks) ? min($risks) : 0,
                'max_risk'            => !empty($risks) ? max($risks) : 0,
                'avg_risk'            => !empty($risks) ? round(array_sum($risks) / count($risks), 2) : 0,
                'total_precipitation' => round($data['total_precipitation'], 2),
                'avg_humidity'        => !empty($humidities) ? round(array_sum($humidities) / count($humidities), 2) : 0,
            ];
        }

        return $result;
    }

    /**
     * Simulate a monthly baseline using Belgian climate averages.
     *
     * Used as a fallback when no real forecast data is available for a given month.
     * Precipitation and humidity values are approximate historical averages for Belgium.
     * The risk score is derived using the same formula as {@see RiskCalculationService}:
     * rain chance (capped at 100) weighted at 50%, humidity weighted at 30%.
     *
     * @param int $month Calendar month number (1–12).
     *
     * @return array Simulated baseline containing:
     *               'min_risk', 'max_risk', 'avg_risk',
     *               'total_precipitation' (mm), 'avg_humidity' (%).
     */
    private function simulateBaseForMonth(int $month): array
    {
        // Average monthly precipitation (mm) for Belgium
        $belgiumAvgPrecip = [
            1 => 72, 2 => 55, 3 => 65, 4 => 52, 5 => 62, 6 => 68,
            7 => 73, 8 => 76, 9 => 68, 10 => 78, 11 => 80, 12 => 78
        ];

        // Average monthly humidity (%) for Belgium
        $belgiumAvgHumidity = [
            1 => 87, 2 => 84, 3 => 81, 4 => 77, 5 => 75, 6 => 74,
            7 => 74, 8 => 76, 9 => 80, 10 => 84, 11 => 87, 12 => 88
        ];

        $precip   = $belgiumAvgPrecip[$month] ?? 70;
        $humidity = $belgiumAvgHumidity[$month] ?? 80;

        // Simulate risk from precip and humidity (same formula as RiskCalculationService)
        $rainChance = min(100, ($precip / 100) * 80);
        $baseRisk   = round($rainChance * 0.5 + $humidity * 0.3);

        return [
            'min_risk'            => max(0, $baseRisk - 10),
            'max_risk'            => min(100, $baseRisk + 10),
            'avg_risk'            => $baseRisk,
            'total_precipitation' => $precip,
            'avg_humidity'        => $humidity,
        ];
    }

    /**
     * Determine the meteorological season for a given month.
     *
     * Uses standard Northern Hemisphere season boundaries:
     * winter (Dec–Feb), spring (Mar–May), summer (Jun–Aug), autumn (Sep–Nov).
     *
     * @param int $month Calendar month number (1–12).
     *
     * @return string Season name: 'winter', 'spring', 'summer', or 'autumn'.
     */
    private function getSeason(int $month): string
    {
        return match (true) {
            in_array($month, [12, 1, 2]) => 'winter',
            in_array($month, [3, 4, 5])  => 'spring',
            in_array($month, [6, 7, 8])  => 'summer',
            default                       => 'autumn',
        };
    }

    /**
     * Build a human-readable reason string explaining why a month was flagged as high-risk.
     *
     * Checks three conditions independently and concatenates all that apply:
     * avg_risk >= RISK_THRESHOLD, is_extreme flag, and total_precipitation >= 80 mm.
     * Always returns a non-empty string ending with a period.
     *
     * @param array $prediction Prediction entry as produced by {@see generateFiveYearPrediction()}.
     *                          Must contain: 'avg_risk' (float), 'is_extreme' (bool),
     *                          'total_precipitation' (float).
     *
     * @return string Concatenated reason string in Dutch, e.g.:
     *                "Gemiddeld risico (75.5) overschrijdt drempelwaarde. Hoge neerslag (85 mm)."
     */
    private function buildRiskReason(array $prediction): string
    {
        $reasons = [];

        if ($prediction['avg_risk'] >= self::RISK_THRESHOLD) {
            $reasons[] = "Gemiddeld risico ({$prediction['avg_risk']}) overschrijdt drempelwaarde";
        }
        if ($prediction['is_extreme']) {
            $reasons[] = "Extreme weersomstandigheden verwacht";
        }
        if ($prediction['total_precipitation'] >= 80) {
            $reasons[] = "Hoge neerslag ({$prediction['total_precipitation']} mm)";
        }

        return implode('. ', $reasons) . '.';
    }
}
