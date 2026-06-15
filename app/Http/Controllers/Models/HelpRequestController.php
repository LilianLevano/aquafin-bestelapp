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
        $requests = HelpRequest::all();
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
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                    'title' => 'required',
                    'description' => 'required'
                ]);
                HelpRequest::create($validated);
            },
            [
                200 => [
                    'message' => 'Jouw aanvraag werd gestuurd!',
                    'route' => route('home', absolute: false)],
                422 => [
                    'message' => 'Jouw aanvraag werd niet doorgestuurd, probeer het opnieuw later.',
                    'route' => url()->previous()],
                500 => [
                    'message' => 'Jouw aanvraag werd niet doorgestuurd, probeer het opnieuw later.',
                    'route' => url()->previous()]
            ]
        );
    }
}
