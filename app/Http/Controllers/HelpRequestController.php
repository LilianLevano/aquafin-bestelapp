<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpRequest;

class HelpRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'  => 'required|string|max:255',
                'last_name'   => 'required|string|max:255',
                'email'       => 'required|email',
                'category'    => 'required|string',
                'description' => 'required|string',
            ]);

            HelpRequest::create([
                'name'        => trim($validated['first_name'] . ' ' . $validated['last_name']),
                'email'       => $validated['email'],
                'category'    => $validated['category'],
                'description' => $validated['description'],
                'is_completed' => false,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Verzoek verstuurd.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Controleer de ingevulde velden.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Er ging iets mis met het verzoeken voor hulp, neem contact op met de IT-dienst.',
            ], 500);
        }
    }
}
