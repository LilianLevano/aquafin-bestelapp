<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Materiaal;
use App\Models\Site;
use Illuminate\Http\Request;

class TechniekerBestellingController extends Controller
{


    /**
     * 
     * Display a listing of the resource.
     */


    public function index()
    {
        $materialen = Materiaal::all();
        $sites = Site::all();
        return view('technieker-bestellen', compact('materialen', 'sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        $validated = $request->validate([
            'materialen' => ['nullable','array'],
            'quantity' => ['nullable','array'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'site_id' => ['required', 'exists:sites,id'],
        ]);



        $pivotData = [];

        foreach ($request->materialen ?? [] as $materiaalId) {
            $pivotData[$materiaalId] = [
                'quantity' => $request->quantity[$materiaalId]
            ];
        }



        unset($validated['materialen']);
        unset($validated['quantity']);

        $validated['user_id'] = auth()->user()->id ?? 1;

        $bestelling = Bestelling::create($validated);


        $bestelling->materiaal()->sync($pivotData);
        return back()->with('status', 'Bestelling opgeslagen');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
