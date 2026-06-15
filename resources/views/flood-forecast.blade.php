@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">
            Overstromingsvoorspelling
        </h1>
        @isset($data)
            <p>Status: {{ $data['status'] ?? '' }}</p>
            <p>Message: {{ $data['message'] ?? '' }}</p>
            <p>Weather data: {{ print_r($data['weatherData'] ?? []) }}</p>
            <p>User data: {{ print_r($data['user'] ?? []) }}</p>
        @else
            <p>No data acquired from the controller.</p>
        @endisset

        <!-- Tabs -->
        <div class="flex gap-4 mb-4">
            <button
                id="trendBtn"
                class="px-4 py-2 bg-blue-500 text-white rounded"
            >
                Trendgrafiek
            </button>

            <button
                id="evolutionBtn"
                class="px-4 py-2 bg-gray-500 text-white rounded"
            >
                Evolutiegrafiek
            </button>
        </div>

        <!-- Mixed Mode -->
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    id="mixedMode"
                >
                Gemengd
            </label>
        </div>

        <!-- Trend Chart -->
        <div id="trendContainer">
            <canvas id="trendChart"></canvas>
        </div>

        <!-- Evolution Chart -->
        <div id="evolutionContainer" class="hidden">
            <canvas id="evolutionChart"></canvas>
        </div>

        <!-- Combined Charts -->
        <div
            id="combinedContainer"
            class="hidden space-y-6 mt-6"
        >
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/flood-forecast.js')
@endpush
