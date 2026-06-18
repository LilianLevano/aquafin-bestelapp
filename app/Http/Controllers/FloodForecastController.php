<?php

namespace App\Http\Controllers;

use App\Http\Controllers\WebController;
use App\Services\WeatherService;
use App\Services\RiskCalculationService;
use App\Services\FloodForecastService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Override;

/**
 * Handles flood forecast display and weather data retrieval for the authenticated user's site.
 *
 * Delegates weather fetching, risk calculation, and prediction generation to dedicated services:
 * {@see WeatherService}, {@see RiskCalculationService}, and {@see FloodForecastService}.
 * Forecast results are cached per site and forecast window to avoid redundant API calls.
 */
class FloodForecastController extends WebController
{
    /**
     * Inject the services required for forecast generation.
     *
     * @param WeatherService        $weatherService  Fetches raw weather data from Open-Meteo.
     * @param RiskCalculationService $riskService    Processes daily records and computes risk values.
     * @param FloodForecastService  $forecastService Generates and persists multi-year flood predictions.
     */
    public function __construct(
        protected WeatherService $weatherService,
        protected RiskCalculationService $riskService,
        protected FloodForecastService $forecastService
    ) {}

    /**
     * Display the flood forecast page.
     *
     * Calls {@see api()} internally to retrieve forecast data and flashes
     * the resulting "message", "success", and "data" keys to the session
     * before rendering the view.
     *
     * @return View
     * @throws ValidationException If the user's site location is not configured.
     */
    #[Override]
    public function index(): View
    {
        $response = $this->api(request())->getData(true);

        // Flash the session data
        foreach (['message', 'success', 'data'] as $key) {
            if (isset($response[$key])) {
                session()->flash($key, $response[$key]);
            }
        }

        return view('flood-forecast');
    }

    /**
     * Fetch weather forecast data from the Open-Meteo API for the authenticated user's site.
     *
     * Accepts an optional "days_ahead" query parameter (integer, 1–14, default: 7).
     * Throws a ValidationException if the value is out of range or if the authenticated
     * user has no site with configured latitude/longitude coordinates.
     *
     * Results are cached per site and forecast window for 30 minutes to avoid
     * redundant external API calls. The cache key format is:
     * "weather_forecast_{siteId}_{days_ahead}".
     *
     * The pipeline executed inside the cache callback:
     *  1. Fetch raw hourly/daily weather data via {@see WeatherService::fetchForecast()}.
     *  2. Process daily records and attach risk values via {@see RiskCalculationService::processDailyRecords()}.
     *  3. Compute a weekly summary via {@see RiskCalculationService::calculateWeeklySummary()}.
     *  4. Generate a 5-year flood prediction and persist it to the database via
     *     {@see FloodForecastService::generateFiveYearPrediction()} and
     *     {@see FloodForecastService::savePredictions()}.
     *
     * The response payload includes:
     *  - "daily"         — processed daily records with risk values.
     *  - "summary"       — weekly risk summary.
     *  - "riskThreshold" — the global risk threshold constant from {@see RiskCalculationService}.
     *  - "days"          — the effective forecast window used.
     *  - "raw"           — the raw weather data returned by the API.
     *  - "fiveYear"      — prediction analyses (first 24 months) and high-risk months.
     *
     * @param Request $request The incoming HTTP request, optionally carrying "days_ahead".
     *
     * @return JsonResponse The forecast payload wrapped in a standard JSON response.
     * @throws ValidationException If "days_ahead" is out of range (1–14) or the site
     *                             location is not configured for the authenticated user.
     */
    public function api(Request $request): JsonResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $days_ahead = (int) $request->query('days_ahead', 7);
                if ($days_ahead < 1 || $days_ahead > 14) {
                    throw ValidationException::withMessages([
                        'days_ahead' => 'Aantal dagen moet tussen 1 en 14 zijn.'
                    ]);
                }

                $user = Auth::user();
                if (
                    !$user ||
                    !$user->site ||
                    !isset($user->site->latitude) ||
                    !isset($user->site->longitude)
                ) {
                    throw ValidationException::withMessages([
                        'site' => 'Site locatie is niet geconfigureerd.'
                    ]);
                }

                $siteId = $user->site->id;
                $latitude = $user->site->latitude;
                $longitude = $user->site->longitude;
                $cacheKey = "weather_forecast_{$siteId}_{$days_ahead}";

                $result = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($latitude, $longitude, $days_ahead, $siteId) {
                    // 1. Fetch raw weather data
                    $raw = $this->weatherService->fetchForecast($latitude, $longitude, $days_ahead);

                    // 2. Process daily records with risk values
                    $dailyRecords = $this->riskService->processDailyRecords($raw, $days_ahead);

                    // 3. Calculate weekly summary
                    $weeklySummary = $this->riskService->calculateWeeklySummary($dailyRecords);

                    // 4. Generate 5-year prediction + save to DB
                    $predictions = $this->forecastService->generateFiveYearPrediction($dailyRecords, $siteId);
                    $saved = $this->forecastService->savePredictions($predictions, $siteId);

                    return [
                        'daily'         => $dailyRecords,
                        'summary'       => $weeklySummary,
                        'riskThreshold' => RiskCalculationService::RISK_THRESHOLD,
                        'days'          => $days_ahead,
                        'raw'           => $raw,
                        'fiveYear'      => [
                            'analyses'   => array_slice($saved['analyses'], 0, 24), // first 2 years for frontend
                            'riskMonths' => $saved['riskMonths'],
                        ],
                    ];
                });

                return $result;
            },
            [
                200 => [
                    'message' => 'Voorspellingen succesvol opgehaald!'],
                422 => [
                    'message' => 'Ongeldige invoer voor aantal dagen vooruit.'],
                500 => [
                    'message' => 'Er ging iets mis bij het ophalen van de overstromingsgegevens.'],
            ],
            JsonResponse::class
        );
    }
}
