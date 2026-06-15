<?php

namespace App\Http\Controllers\FloodForecast;

use App\Http\Controllers\WebController;
use BadMethodCallException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Override;

class FloodForecastController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        // https://archive-api.open-meteo.com/v1/archive?latitude=52.52&longitude=13.41&start_date=2026-05-24&end_date=2026-06-07&hourly=temperature_2m

        // return view('dashboard', compact());
        $days_ahead = request()->input('days_ahead');
        $response = $this->api(request(), $days_ahead);

        $data = $response->getData(true); // Decodes to associative array
        return view('flood-forecast', compact('data'));
    }

    /**
     */
    public function api(Request $request, $days_ahead = null)
    {
        // Default values (Berlin as example)
        $latitude = Auth::user()->site->latitude;
        $longitude = Auth::user()->site->longitude;

        // Determine start and end date based on $days_ahead
        $today = now();
        $start_date = $today->copy();

        // If days_ahead is given, add that many days to end_date (limited to 14 days)
        // Limit $days_ahead to a maximum of 14 days
        $maxDaysAhead = 14;
        if ($days_ahead !== null) {
            $days = min((int) $days_ahead, $maxDaysAhead);
            $end_date = $start_date->copy()->addDays($days);
        } else {
            $end_date = $start_date->copy()->addDays(7);
        }

        $apiUrl = "https://archive-api.open-meteo.com/v1/archive";
        $query = http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'start_date' => $start_date->toDateString(),
            'end_date' => $end_date->toDateString(),
            'hourly' => 'temperature_2m',
        ]);
        $fullUrl = "{$apiUrl}?{$query}";

        // Simple implementation using Laravel's HTTP client
        $response = \Illuminate\Support\Facades\Http::get($fullUrl);
        $weatherData = $response->json();

        return response()->json([
            'status' => 'success',
            'message' => 'Forecasts acquired.',
            'weatherData' => $weatherData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    /**
     * Display the specified resource.
     */
    #[Override]
    public function show(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Override]
    public function destroy(string $id): RedirectResponse
    {
        throw new BadMethodCallException('Non-implemented method: index().', 1);
    }
}
