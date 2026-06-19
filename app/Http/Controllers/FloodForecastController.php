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
 * Delegates weather fetching and risk calculation to dedicated services:
 * {@see WeatherService} and {@see RiskCalculationService}.
 * Forecast results are cached per site and forecast window to avoid redundant API calls.
 */
class FloodForecastController extends WebController
{
    /**
     * Inject the services required for forecast generation.
     *
     * @param WeatherService        $weatherService  Fetches raw weather data from Open-Meteo.
     * @param RiskCalculationService $riskService    Processes daily records and computes risk values.
     * @param FloodForecastService  $forecastService Reserved for historical risk analysis (currently unused by this endpoint).
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
     * Retrieve the daily forecast and precipitation/risk summary for the authenticated
     * user's site, using {@see WeatherService::fetchForecast()} and
     * {@see RiskCalculationService::processDailyRecords()} / {@see RiskCalculationService::calculateWeeklySummary()}.
     *
     * The response payload includes:
     *  - "daily"         — processed daily records with risk values.
     *  - "summary"       — weekly risk summary.
     *  - "riskThreshold" — the global risk threshold constant from {@see RiskCalculationService}.
     *  - "days"          — the effective forecast window used.
     *  - "raw"           — the raw weather data returned by the API.
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

                $result = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($latitude, $longitude, $days_ahead) {
                    // 1. Fetch raw weather data (forecast)
                    $raw = $this->weatherService->fetchForecast($latitude, $longitude, $days_ahead);

                    // 2. Process daily records with risk values
                    $dailyRecords = $this->riskService->processDailyRecords($raw, $days_ahead);

                    // 3. Calculate weekly summary
                    $weeklySummary = $this->riskService->calculateWeeklySummary($dailyRecords);

                    return [
                        'daily'         => $dailyRecords,
                        'summary'       => $weeklySummary,
                        'riskThreshold' => RiskCalculationService::RISK_THRESHOLD,
                        'days'          => $days_ahead,
                        'raw'           => $raw,
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