<?php

namespace App\Http\Controllers\FloodForecast;

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
use Exception;

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
        return view('flood-forecast');
    }

    /**
     * API endpoint — returns processed forecast + risk data as JSON.
     */
    public function api(Request $request): JsonResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $days = (int) $request->input('days_ahead', 7);

                if ($days < 1 || $days > 14) {
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
                        'site' => 'Gebruikerslocatie is niet geconfigureerd.'
                    ]);
                }

                $siteId    = $user->site->id;
                $latitude  = $user->site->latitude;
                $longitude = $user->site->longitude;
                $cacheKey  = "weather_forecast_{$siteId}_{$days}";

                $result = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($latitude, $longitude, $days, $siteId) {
                    // 1. Fetch raw weather data
                    $raw = $this->weatherService->fetchForecast($latitude, $longitude, $days);

                    // 2. Process daily records with risk values
                    $dailyRecords = $this->riskService->processDailyRecords($raw, $days);

                    // 3. Calculate weekly summary
                    $weeklySummary = $this->riskService->calculateWeeklySummary($dailyRecords);

                    // 4. Generate 5-year prediction + save to DB
                    $predictions = $this->forecastService->generateFiveYearPrediction($dailyRecords, $siteId);
                    $saved       = $this->forecastService->savePredictions($predictions, $siteId);

                    return [
                        'daily'         => $dailyRecords,
                        'summary'       => $weeklySummary,
                        'riskThreshold' => RiskCalculationService::RISK_THRESHOLD,
                        'days'          => $days,
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
                200 => ['message' => 'Voorspellingen succesvol opgehaald!'],
                422 => ['message' => 'Ongeldige invoer voor aantal dagen vooruit.'],
                500 => ['message' => 'Er ging iets mis bij het ophalen van de overstromingsgegevens.'],
            ],
            JsonResponse::class
        );
    }
}