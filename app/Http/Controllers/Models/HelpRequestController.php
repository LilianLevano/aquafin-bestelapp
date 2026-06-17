<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Override;

class HelpRequestController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $is_completed = request()->query('is_completed', 'completed');

        if ($is_completed === 'completed'){
            $requests = HelpRequest::all()->where('is_completed', 1)->sortByDesc('created_at');
        } else if ($is_completed === 'open'){
            $requests = HelpRequest::all()->where('is_completed', 0)->sortByDesc('created_at');
        } else {
            $requests = HelpRequest::all();
        }

        return view('help-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        return view('help-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Override]
    public function store(Request $request): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request) {
                $validated = $request->validate([
                    'first_name' => ['required', 'min:2'],
                    'last_name' => ['required', 'min:2'],
                    'email' => ['required','email'],
                    'title' => ['required', 'max:50'],
                    'description' => ['required', 'max:400'],
                ]);
                HelpRequest::create($validated);
            },
            [
                200 => [
                    'message' => 'Jouw aanvraag werd opgeslagen!',
                    'route' => route('home', absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('home', absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('home', absolute: true)]
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    #[Override]
    public function edit(string $id): View
    {
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.partials.answer', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    #[Override]
    public function update(Request $request, string $id): RedirectResponse
    {
        return $this->handleWithCases(
            $request,
            function () use ($request, $id) {
                $validated = $request->validate([
                    'answer' => ['required']
                ]);

                $helpRequest = HelpRequest::findOrFail($id);

                $helpRequest->is_completed = 1;
                $helpRequest->updateOrFail($validated);
            },
            [
                200 => [
                    'message' => 'Hulp aanvraag succesvol geüpdatet!',
                    'route' => route('admin.help-requests.index', ['is_completed' => 'all'], absolute: true)],
                422 => [
                    'message' => 'Er was iets mis met de validatie, check uw input.',
                    'route' => route('admin.help-requests.edit', ['help-request' => $id], absolute: true)],
                500 => [
                    'message' => 'Er ging iets intern miss, neem contact op met de IT dienst.',
                    'route' => route('admin.help-requests.edit', ['help-request' => $id], absolute: true)]
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    #[Override]
    public function show($id): View
    {
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.show', compact('request'));
    }
}
