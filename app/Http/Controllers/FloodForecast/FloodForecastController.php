<?php

namespace App\Http\Controllers\FloodForecast;

use App\Http\Controllers\WebController;
use App\Services\WeatherService;
use App\Services\RiskCalculationService;
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
        protected RiskCalculationService $riskService
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
     * Cached for 30 minutes. Used by the frontend JS.
     */
    public function api(Request $request): JsonResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                // Validate days_ahead param
                $days = (int) $request->input('days_ahead', 7);

                if ($days < 1 || $days > 14) {
                    throw ValidationException::withMessages([
                        'days_ahead' => 'Aantal dagen moet tussen 1 en 14 zijn.'
                    ]);
                }

                // Validate user has a site with coordinates
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

                $latitude  = $user->site->latitude;
                $longitude = $user->site->longitude;

                // Use cache — re-fetch if not available
                $cacheKey = "weather_forecast_{$user->id}_{$days}";

                $result = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($latitude, $longitude, $days) {
                    // Fetch raw data from Open-Meteo
                    $raw = $this->weatherService->fetchForecast($latitude, $longitude, $days);

                    // Process into daily records with risk values
                    $dailyRecords = $this->riskService->processDailyRecords($raw, $days);

                    // Calculate weekly summary (min, max, avg risk + risk days)
                    $weeklySummary = $this->riskService->calculateWeeklySummary($dailyRecords);

                    return [
                        'daily'         => $dailyRecords,
                        'summary'       => $weeklySummary,
                        'riskThreshold' => RiskCalculationService::RISK_THRESHOLD,
                        'days'          => $days,
                        'raw'           => $raw, // kept for frontend chart compatibility
                    ];
                });

                return $result;
            },
            [
                200 => ['message' => 'Voorspellingen succesvol opgehaald!'],
                422 => ['message' => 'Ongeldige invoer voor aantal dagen vooruit.'],
                500 => ['message' => 'Fout bij het ophalen van gegevens van de Open-Meteo API.'],
            ],
            JsonResponse::class
        );
    }
}