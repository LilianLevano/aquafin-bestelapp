<?php

namespace App\Services;

/**
 * Processes raw Open-Meteo weather data into daily risk records and weekly summaries.
 *
 * Risk values are computed from hourly precipitation probability and relative humidity
 * using a weighted formula. Days with a risk value at or above {@see RISK_THRESHOLD}
 * are flagged as flood risk days.
 */
class RiskCalculationService
{
    /**
     * Minimum risk score (0–100) at or above which a day is considered a flood risk day.
     *
     * Shared with {@see FloodForecastService::RISK_THRESHOLD} — both must stay in sync
     * if the threshold is ever adjusted.
     *
     * @var int
     */
    const RISK_THRESHOLD = 70;

    /**
     * Process raw Open-Meteo API data into a flat array of daily weather and risk records.
     *
     * For each day in the forecast window, extracts daily min/max temperature and
     * precipitation from the "daily" block, then scans the "hourly" block to collect
     * all hourly humidity and precipitation probability values that fall on that day.
     * Hourly values are averaged and fed into {@see calculateRisk()} to produce a
     * risk score. If either averaged value is null (no hourly data for that day),
     * the risk value and isRisk flag are null and false respectively.
     *
     * Expected structure of $raw (as returned by WeatherService::fetchForecast()):
     * <pre>
     * [
     *   'daily' => [
     *     'time'                  => string[],   // date strings e.g. "2025-06-01"
     *     'temperature_2m_min'    => float[],
     *     'temperature_2m_max'    => float[],
     *     'precipitation_sum'     => float[],
     *   ],
     *   'hourly' => [
     *     'time'                        => string[],   // datetime strings e.g. "2025-06-01T13:00"
     *     'relative_humidity_2m'        => float[],
     *     'precipitation_probability'   => float[],
     *   ],
     * ]
     * </pre>
     *
     * Each returned record contains:
     * 'date', 'minTemp', 'maxTemp', 'avgTemp', 'humidity', 'rainChance',
     * 'rainMm', 'riskValue' (int|null), 'isRisk' (bool).
     *
     * @param array $raw  Raw API response from Open-Meteo.
     * @param int   $days Maximum number of days to process (capped at available data length).
     *
     * @return array<int, array> Flat array of daily records indexed from 0.
     */
    public function processDailyRecords(array $raw, int $days): array
    {
        $results = [];
        $numDays = min(count($raw['daily']['time']), $days);

        for ($i = 0; $i < $numDays; $i++) {
            $dayStr  = $raw['daily']['time'][$i];
            $minTemp = $raw['daily']['temperature_2m_min'][$i] ?? null;
            $maxTemp = $raw['daily']['temperature_2m_max'][$i] ?? null;
            $rainMm  = $raw['daily']['precipitation_sum'][$i] ?? null;

            $humidityVals   = [];
            $rainChanceVals = [];

            foreach ($raw['hourly']['time'] as $h => $hourStr) {
                if (str_starts_with($hourStr, $dayStr)) {
                    $humidityVals[]   = $raw['hourly']['relative_humidity_2m'][$h] ?? null;
                    $rainChanceVals[] = $raw['hourly']['precipitation_probability'][$h] ?? null;
                }
            }

            $humidity   = $this->average(array_filter($humidityVals,   fn($v) => $v !== null));
            $rainChance = $this->average(array_filter($rainChanceVals, fn($v) => $v !== null));
            $riskValue  = $this->calculateRisk($rainChance, $humidity);

            $results[] = [
                'date'       => $dayStr,
                'minTemp'    => $minTemp !== null ? round($minTemp) : null,
                'maxTemp'    => $maxTemp !== null ? round($maxTemp) : null,
                'avgTemp'    => ($minTemp !== null && $maxTemp !== null) ? round(($minTemp + $maxTemp) / 2, 1) : null,
                'humidity'   => $humidity   !== null ? round($humidity)   : null,
                'rainChance' => $rainChance !== null ? round($rainChance) : null,
                'rainMm'     => $rainMm     !== null ? round($rainMm, 1)  : null,
                'riskValue'  => $riskValue,
                'isRisk'     => $riskValue !== null && $riskValue >= self::RISK_THRESHOLD,
            ];
        }

        return $results;
    }

    /**
     * Calculate a weekly summary of risk values across all daily records.
     *
     * Extracts all non-null 'riskValue' entries, computes min, max, and average,
     * and collects all days flagged as 'isRisk' into a 'riskDays' array.
     * Returns null for all numeric fields if no valid risk values are present.
     *
     * @param array $dailyRecords Array of daily records as returned by {@see processDailyRecords()}.
     *
     * @return array{
     *     min: int|null,
     *     max: int|null,
     *     avg: float|null,
     *     riskDays: array
     * }
     */
    public function calculateWeeklySummary(array $dailyRecords): array
    {
        $riskValues = array_filter(array_column($dailyRecords, 'riskValue'), fn($v) => $v !== null);

        if (empty($riskValues)) {
            return ['min' => null, 'max' => null, 'avg' => null, 'riskDays' => []];
        }

        return [
            'min'      => min($riskValues),
            'max'      => max($riskValues),
            'avg'      => round(array_sum($riskValues) / count($riskValues), 1),
            'riskDays' => array_values(array_filter($dailyRecords, fn($d) => $d['isRisk'])),
        ];
    }


    /**
     * Compute a flood risk score from average rain chance and humidity.
     *
     * Formula: risk = (rainChance × 0.5) + (humidity × 0.3), rounded to nearest integer.
     * The result is an integer in the range 0–100 (uncapped — extreme inputs could
     * theoretically exceed 100 if both inputs are at their maximum).
     * Returns null if either input is null.
     *
     * @param float|null $rainChance Average hourly precipitation probability (0–100).
     * @param float|null $humidity   Average hourly relative humidity (0–100).
     *
     * @return int|null Computed risk score, or null if inputs are insufficient.
     */
    private function calculateRisk(?float $rainChance, ?float $humidity): ?int
    {
        if ($rainChance === null || $humidity === null) return null;
        return (int) round($rainChance * 0.5 + $humidity * 0.3);
    }

    /**
     * Compute the arithmetic mean of a non-empty array of numeric values.
     *
     * Returns null if the array is empty. The caller is responsible for
     * pre-filtering null values before passing the array.
     *
     * @param array $values Flat array of numeric values (no nulls).
     *
     * @return float|null The average, or null if the array is empty.
     */
    private function average(array $values): ?float
    {
        $values = array_values($values);
        if (empty($values)) return null;
        return array_sum($values) / count($values);
    }
}
