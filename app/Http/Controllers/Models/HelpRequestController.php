<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Override;

/**
 * Handles CRUD operations for help requests.
 *
 * Visitors can submit help requests without authentication via {@see create()} and {@see store()}.
 * Admins can view, answer, and close requests via {@see index()}, {@see edit()},
 * {@see update()}, and {@see show()}.
 */
class HelpRequestController extends WebController
{
    /**
     * Display a listing of help requests filtered by their completion status.
     *
     * Accepts an optional query parameter "is_completed":
     * - "completed" — returns only completed requests, sorted by creation date descending.
     * - "open"      — returns only open (unanswered) requests, sorted by creation date descending.
     * - any other value — returns all requests unsorted.
     *
     * Defaults to "completed" if no query parameter is provided.
     *
     * @return View
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
     * Show the form for submitting a new help request.
     *
     * Accessible without authentication.
     *
     * @return View
     */
    #[Override]
    public function create(): View
    {
        return view('help-requests.create');
    }

    /**
     * Store a newly created help request in storage.
     *
     * Accessible without authentication.
     * On success, validation error (422), or server error (500),
     * redirects to the home page with a status message.
     *
     * @param Request $request The incoming HTTP request containing help request form data.
     *
     * @return RedirectResponse Redirects to the home page with a status message.
     * @throws ValidationException If any required field fails validation.
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
     * Show the form for submitting an admin answer to the specified help request.
     *
     * Although named edit() to satisfy the resourceful controller contract,
     * this method renders a dedicated answer partial rather than a generic edit form.
     *
     * @param string $id The primary key of the help request to answer.
     *
     * @return View
     * @throws ModelNotFoundException If no help request exists with the given ID.
     */
    #[Override]
    public function edit(string $id): View
    {
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.partials.answer', compact('request'));
    }

    /**
     * Store the admin's answer and mark the help request as completed.
     *
     * Sets "is_completed" to 1 directly on the model instance before calling
     * updateOrFail(), so both the answer and the completion flag are persisted
     * in a single database write.
     * On success, redirects to the full listing.
     * On validation error (422) or server error (500), redirects back to the answer form.
     *
     * @param Request $request The incoming HTTP request containing the admin's answer.
     * @param string  $id      The primary key of the help request to update.
     *
     * @return RedirectResponse Redirects to the help request index on success,
     *                          or back to the answer form on error.
     * @throws ValidationException    If the answer field is missing or invalid.
     * @throws ModelNotFoundException If no help request exists with the given ID.
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
     * Display the details of the specified help request.
     *
     * @param string $id The primary key of the help request to display.
     *
     * @return View
     * @throws ModelNotFoundException If no help request exists with the given ID.
     */
    #[Override]
    public function show(string $id): View
    {
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.show', compact('request'));
    }
}
