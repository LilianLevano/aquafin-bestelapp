<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Carbon\Carbon;

class HelpRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'title' => 'required',
                'description' => 'required'
            ]);

            HelpRequest::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'posted_on' => Carbon::now(),
                'is_completed' => false
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Verzoek verstuurd.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Er ging iets mis met het verzoeken voor hulp, neem contact op met de IT-dienst.'
            ], 500);
        }
    }
}
