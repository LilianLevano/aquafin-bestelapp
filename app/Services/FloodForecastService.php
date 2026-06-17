<?php

namespace App\Services;

use App\Models\FloodRiskAnalysis;
use App\Models\RiskMonth;
use Carbon\Carbon;

class FloodForecastService
{
    /**
     * Risk threshold for identifying risk months.
     */
    const RISK_THRESHOLD = 70;

    /**
     * Seasonal precipitation multipliers for simulation.
     * Belgium climate: wetter in winter/autumn, drier in summer.
     */
    const SEASONAL_MULTIPLIERS = [
        'winter' => 1.3,
        'spring' => 1.0,
        'summer' => 0.8,
        'autumn' => 1.2,
    ];

    /**
     * Generate a 5-year monthly flood risk prediction based on
     * current Open-Meteo forecast data and seasonal patterns.
     *
     * @param array $dailyRecords Processed daily records from RiskCalculationService
     * @param int $siteId
     * @return array
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
     * Save predictions to database and identify risk months.
     *
     * @param array $predictions
     * @param int $siteId
     * @return array ['analyses' => [...], 'riskMonths' => [...]]
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
     * Get cached predictions from DB for a site.
     *
     * @param int $siteId
     * @return array|null
     */
    public function getCachedPredictions(int $siteId): ?array
    {
        $analyses = FloodRiskAnalysis::where('site_id', $siteId)
            ->where('year', '>=', now()->year)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        if ($analyses->isEmpty()) {
            return null;
        }

        $riskMonths = RiskMonth::where('site_id', $siteId)
            ->where('year', '>=', now()->year)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'analyses'   => $analyses->toArray(),
            'riskMonths' => $riskMonths->toArray(),
        ];
    }

    /**
     * Aggregate daily records into monthly summaries.
     *
     * @param array $dailyRecords
     * @return array keyed by month number
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
     * Simulate base monthly data using Belgium climate averages.
     * Used when no real data is available for a given month.
     *
     * @param int $month
     * @return array
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
     * Determine season from month number.
     *
     * @param int $month
     * @return string
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
     * Build a human-readable reason string for a risk month.
     *
     * @param array $prediction
     * @return string
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