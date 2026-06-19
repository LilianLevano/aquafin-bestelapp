<?php

namespace App\Services;

use App\Models\FloodRiskAnalysis;
use App\Models\RiskMonth;
use Carbon\Carbon;
use Exception;

/**
 * Generates, persists, and retrieves historical flood risk analyses for a given site.
 *
 * Historical precipitation, temperature, and humidity data is fetched via
 * {@see WeatherService::fetchHistorical()} (Open-Meteo Archive API) since a
 * configurable start year (default: 2004, matching the KMI reference period
 * mentioned in the Aquafin assignment). The data is processed using the same
 * risk formula as live forecasts ({@see RiskCalculationService}), then
 * aggregated per calendar month across all available years to produce
 * real, data-grounded monthly risk statistics — replacing speculative
 * multi-year prediction with grounded historical analysis.
 *
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
     * Earliest year to fetch historical data from, matching the KMI reference
     * period referenced in the Aquafin assignment ("neerslag per maand sinds 2004").
     */
    const HISTORICAL_START_YEAR = 2004;

    public function __construct(
        protected WeatherService $weatherService,
        protected RiskCalculationService $riskService
    ) {}

    /**
     * Generate a historical monthly flood risk analysis for the given site.
     *
     * Fetches real historical weather data year by year (Open-Meteo Archive API
     * is queried per calendar year to stay within practical request sizes),
     * processes each year's daily records into risk values via
     * {@see RiskCalculationService::processDailyRecords()}, then aggregates
     * all years together per calendar month (Jan–Dec) into min/max/avg risk,
     * total precipitation, and average humidity.
     *
     * A month is flagged as extreme if its historical max risk >= 90
     * or average total precipitation per occurrence >= 100 mm.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int   $siteId
     * @param int   $startYear Earliest year to include (default: {@see HISTORICAL_START_YEAR}).
     *
     * @return array Flat array of monthly analysis entries (1 per calendar month, 12 total), each containing:
     *               'site_id', 'year', 'month', 'min_risk', 'max_risk', 'avg_risk',
     *               'total_precipitation', 'avg_humidity', 'season', 'is_extreme'.
     *               Note: 'year' here represents the analysis year (current year),
     *               since the value itself summarizes multiple historical years.
     */
    public function generateHistoricalAnalysis(float $latitude, float $longitude, int $siteId, int $startYear = self::HISTORICAL_START_YEAR): array
    {
        $endYear = now()->year - 1; // last fully completed year
        $monthlyAccumulator = [];   // [month => ['risk_values' => [], 'precip_values' => [], 'humidity_values' => []]]

        for ($year = $startYear; $year <= $endYear; $year++) {
            $start = "{$year}-01-01";
            $end   = "{$year}-12-31";

            try {
                $raw = $this->weatherService->fetchHistorical($latitude, $longitude, $start, $end);
            } catch (Exception $e) {
                // Skip years where the archive has no data (e.g. too recent or API gap)
                continue;
            }

            $daysInYear = count($raw['daily']['time'] ?? []);
            if ($daysInYear === 0) continue;

            $dailyRecords = $this->riskService->processDailyRecords($raw, $daysInYear);

            foreach ($dailyRecords as $day) {
                $month = (int) Carbon::parse($day['date'])->format('m');

                if (!isset($monthlyAccumulator[$month])) {
                    $monthlyAccumulator[$month] = [
                        'risk_values'     => [],
                        'precip_values'   => [],
                        'humidity_values' => [],
                    ];
                }

                if ($day['riskValue'] !== null) {
                    $monthlyAccumulator[$month]['risk_values'][] = $day['riskValue'];
                }
                if ($day['rainMm'] !== null) {
                    $monthlyAccumulator[$month]['precip_values'][] = $day['rainMm'];
                }
                if ($day['humidity'] !== null) {
                    $monthlyAccumulator[$month]['humidity_values'][] = $day['humidity'];
                }
            }
        }

        $currentYear = now()->year;
        $analyses = [];

        for ($month = 1; $month <= 12; $month++) {
            $data = $monthlyAccumulator[$month] ?? ['risk_values' => [], 'precip_values' => [], 'humidity_values' => []];

            $risks      = $data['risk_values'];
            $precips    = $data['precip_values'];
            $humidities = $data['humidity_values'];

            $minRisk  = !empty($risks) ? min($risks) : 0;
            $maxRisk  = !empty($risks) ? max($risks) : 0;
            $avgRisk  = !empty($risks) ? round(array_sum($risks) / count($risks), 2) : 0;
            $totalPrecip = !empty($precips) ? round(array_sum($precips) / max(1, ($endYear - $startYear + 1)), 2) : 0; // avg per year
            $avgHumidity = !empty($humidities) ? round(array_sum($humidities) / count($humidities), 2) : 0;

            $isExtreme = $maxRisk >= 90 || $totalPrecip >= 100;

            $analyses[] = [
                'site_id'              => $siteId,
                'year'                 => $currentYear,
                'month'                => $month,
                'min_risk'             => $minRisk,
                'max_risk'             => $maxRisk,
                'avg_risk'             => $avgRisk,
                'total_precipitation'  => $totalPrecip,
                'avg_humidity'         => $avgHumidity,
                'season'               => $this->getSeason($month),
                'is_extreme'           => $isExtreme,
            ];
        }

        return $analyses;
    }

    /**
     * Persist historical analysis entries to the database and identify risk months.
     *
     * Each analysis is upserted into the {@see FloodRiskAnalysis} table using
     * (site_id, year, month) as the unique key. If an analysis's avg_risk meets
     * or exceeds {@see RISK_THRESHOLD}, a corresponding {@see RiskMonth} record
     * is also upserted with a computed risk reason.
     *
     * @param array $analyses Flat array of analysis entries from {@see generateHistoricalAnalysis()}.
     * @param int   $siteId
     *
     * @return array{analyses: FloodRiskAnalysis[], riskMonths: RiskMonth[]}
     */
    public function saveAnalyses(array $analyses, int $siteId): array
    {
        $savedAnalyses = [];
        $savedRiskMonths = [];

        foreach ($analyses as $entry) {
            $analysis = FloodRiskAnalysis::updateOrCreate(
                [
                    'site_id' => $siteId,
                    'year'    => $entry['year'],
                    'month'   => $entry['month'],
                ],
                $entry
            );

            $savedAnalyses[] = $analysis;

            if ($analysis->avg_risk >= self::RISK_THRESHOLD) {
                $reason = $this->buildRiskReason($entry);

                $riskMonth = RiskMonth::updateOrCreate(
                    [
                        'site_id' => $siteId,
                        'year'    => $entry['year'],
                        'month'   => $entry['month'],
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
     * Retrieve persisted historical analyses for a site from the database.
     *
     * @param int $siteId
     *
     * @return array{analyses: array, riskMonths: array}|null
     */
    public function getCachedAnalyses(int $siteId): ?array
    {
        $analyses = FloodRiskAnalysis::where('site_id', $siteId)
            ->orderBy('month')
            ->get();

        if ($analyses->isEmpty()) {
            return null;
        }

        $riskMonths = RiskMonth::where('site_id', $siteId)
            ->orderBy('month')
            ->get();

        return [
            'analyses'   => $analyses->toArray(),
            'riskMonths' => $riskMonths->toArray(),
        ];
    }

    /**
     * Determine the meteorological season for a given month.
     *
     * @param int $month Calendar month number (1–12).
     * @return string 'winter', 'spring', 'summer', or 'autumn'.
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
     * @param array $entry Analysis entry as produced by {@see generateHistoricalAnalysis()}.
     * @return string
     */
    private function buildRiskReason(array $entry): string
    {
        $reasons = [];

        if ($entry['avg_risk'] >= self::RISK_THRESHOLD) {
            $reasons[] = "Gemiddeld historisch risico ({$entry['avg_risk']}) overschrijdt drempelwaarde";
        }
        if ($entry['is_extreme']) {
            $reasons[] = "Extreme weersomstandigheden vastgesteld in historische data";
        }
        if ($entry['total_precipitation'] >= 80) {
            $reasons[] = "Hoge gemiddelde neerslag ({$entry['total_precipitation']} mm/jaar)";
        }

        return implode('. ', $reasons) . '.';
    }
}