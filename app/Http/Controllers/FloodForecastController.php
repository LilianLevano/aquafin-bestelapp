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

class FloodForecastController extends WebController
{
    public function __construct(
        protected WeatherService $weatherService,
        protected RiskCalculationService $riskService,
        protected FloodForecastService $forecastService
    ) {}

    /**
     * Display the flood forecast page.
     */
    #[Override]
    public function index(): View
    {
        $response = $this->api(request())->getData(true);

        // Flash the session data
        session()->flash('message', $response['message']);
        session()->flash('success', $response['success']);
        session()->flash('data', $response['data']);

        return view('flood-forecast');
    }

    /**
     * Fetch weather forecast data from the Open-Meteo API for the authenticated user's site.
     * Supports custom number of forecast days (default: 7, max: 14).
     * Returns processed weather and risk data (or error details) as JSON with basic API validation.
     *
     * @param Request $request
     * @return JsonResponse
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
