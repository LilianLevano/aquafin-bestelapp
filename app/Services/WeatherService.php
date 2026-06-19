<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

/**
 * Fetches weather forecast data from the Open-Meteo API.
 *
 * Retrieves daily and hourly meteorological data for a given location
 * and forecast window. The returned array is consumed by
 * {@see RiskCalculationService::processDailyRecords()}.
 */
class WeatherService
{
    /**
     * Fetch a weather forecast from the Open-Meteo API for the given coordinates.
     *
     * The forecast window starts today and spans $days_ahead days.
     * The value is clamped to the range [1, 14] regardless of what is passed.
     * Timezone is fixed to "Europe/Berlin" (UTC+1/+2), covering Belgium.
     *
     * Daily fields requested: temperature_2m_max, temperature_2m_min, precipitation_sum.
     * Hourly fields requested: relative_humidity_2m, precipitation_probability.
     *
     * The returned array has the following structure:
     * <pre>
     * [
     *   'daily' => [
     *     'time'                => string[],  // date strings e.g. "2025-06-01"
     *     'temperature_2m_max'  => float[],
     *     'temperature_2m_min'  => float[],
     *     'precipitation_sum'   => float[],
     *   ],
     *   'hourly' => [
     *     'time'                       => string[],  // datetime strings e.g. "2025-06-01T13:00"
     *     'relative_humidity_2m'       => float[],
     *     'precipitation_probability'  => float[],
     *   ],
     *   // ...additional metadata fields returned by Open-Meteo
     * ]
     * </pre>
     *
     * @param float $latitude   Geographic latitude of the site.
     * @param float $longitude  Geographic longitude of the site.
     * @param int   $days_ahead Number of forecast days to retrieve (clamped to 1–14, default: 7).
     *
     * @return array Raw API response array containing 'daily' and 'hourly' blocks.
     * @throws Exception If the HTTP request fails or the response does not contain a 'daily' key.
     */
    public function fetchForecast(float $latitude, float $longitude, int $days_ahead = 7): array
    {
        $days = min(max(1, $days_ahead), 14);

        $today      = now()->startOfDay();
        $start_date = $today->copy();
        $end_date   = $start_date->copy()->addDays($days);

        $query = http_build_query([
            'latitude'   => $latitude,
            'longitude'  => $longitude,
            'start_date' => $start_date->toDateString(),
            'end_date'   => $end_date->toDateString(),
            'daily'      => 'temperature_2m_max,temperature_2m_min,precipitation_sum',
            'hourly'     => 'relative_humidity_2m,precipitation_probability',
            'timezone'   => 'Europe/Berlin',
        ]);

        $response = Http::get("https://api.open-meteo.com/v1/forecast?{$query}");

        if ($response->failed()) {
            throw new Exception('Could not acquire forecasts from Open-Meteo. ' . $response->body());
        }

        $data = $response->json();

        if (!is_array($data) || !array_key_exists('daily', $data)) {
            throw new Exception('Malformed weather data received from API.');
        }

        return $data;
    }
}
