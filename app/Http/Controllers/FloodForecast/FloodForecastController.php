<?php

namespace App\Http\Controllers\FloodForecast;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Override;
use Exception;

class FloodForecastController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $data = cache()->flexible('weather-data', [10000, 20000], function () {
            $days_ahead = request()->input('days_ahead');
            $response = $this->api(request(), $days_ahead);
            return $response->getData(true); // Decodes to associative array
        });

        return view('flood-forecast', compact('data'));
    }

    /**
     * Fetch weather forecast data from the Open-Meteo API for the authenticated user's site.
     * Supports custom number of forecast days (default: 7, max: 14).
     * Returns JSON weather data (or error details) with basic API validation and error handling.
     *
     * @param Request $request
     * @param int $days_ahead Number of days ahead to fetch the forecast. Defaults to 7, max 14.
     * @return JsonResponse
     */
    public function api(Request $request, int $days_ahead = 7): JsonResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $days_ahead) {
                // User/site validation
                $user = Auth::user();
                if (
                    !$user ||
                    !$user->site ||
                    !isset($user->site->latitude) ||
                    !isset($user->site->longitude)
                ) {
                    throw ValidationException::withMessages(['site' => 'User site location not configured.']);
                }

                $latitude  = $user->site->latitude;
                $longitude = $user->site->longitude;

                // Sanitize days_ahead param
                $maxDaysAhead = 14;
                $defaultDays = 7;
                $days = $defaultDays;

                if ($days_ahead !== null) {
                    if (!is_numeric($days_ahead) || $days_ahead < 1) {
                        throw ValidationException::withMessages(['days_ahead' => 'Invalid days_ahead parameter.']);
                    }
                    $days = min((int) $days_ahead, $maxDaysAhead);
                }

                // Calculate start and end date
                $today = now()->startOfDay();
                $start_date = $today->copy();
                $end_date = $start_date->copy()->addDays($days);

                // Assemble the Open-Meteo API query
                $apiUrl = "https://api.open-meteo.com/v1/forecast";
                $query = http_build_query([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'start_date' => $start_date->toDateString(),
                    'end_date' => $end_date->toDateString(),
                    'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_sum',
                    'hourly' => 'relative_humidity_2m,precipitation_probability',
                    'timezone' => 'Europe/Berlin'
                ]);
                $fullUrl = "{$apiUrl}?{$query}";

                // Make HTTP request to Open-Meteo.
                $response = \Illuminate\Support\Facades\Http::get($fullUrl);

                if ($response->failed()) {
                    throw new Exception('Could not acquire forecasts from Open-Meteo. ' . $response->body());
                }

                $weatherData = $response->json();

                // Base structure check: must have 'daily' key in array.
                if (!is_array($weatherData) || !array_key_exists('daily', $weatherData)) {
                    throw new Exception('Malformed weather data received from API.');
                }

                return $weatherData;
            },
            [
                200 => [
                    'message' => 'Voorspellingen succesvol opgehaald!'
                ],
                422 => [
                    'message' => 'Ongeldige invoer voor aantal dagen vooruit.'
                ],
                500 => [
                    'message' => 'Fout bij het ophalen van gegevens van de Open-Meteo API.'
                ]
            ],
            JsonResponse::class
        );
    }
}
