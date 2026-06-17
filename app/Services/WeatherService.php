<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class WeatherService
{
    public function fetchForecast(float $latitude, float $longitude, int $days = 7): array
    {
        $days = min(max(1, $days), 14);

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