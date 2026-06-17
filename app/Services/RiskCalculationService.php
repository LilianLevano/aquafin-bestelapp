<?php

namespace App\Services;

class RiskCalculationService
{
    /**
     * Threshold above which a day is considered to have a flood risk.
     *
     * @var int
     */
    const RISK_THRESHOLD = 70;

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

    private function calculateRisk(?float $rainChance, ?float $humidity): ?int
    {
        if ($rainChance === null || $humidity === null) return null;
        return (int) round($rainChance * 0.5 + $humidity * 0.3);
    }

    private function average(array $values): ?float
    {
        $values = array_values($values);
        if (empty($values)) return null;
        return array_sum($values) / count($values);
    }
}
