<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Materiaal;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bestellingen = Bestelling::with(['user', 'materiaal', 'site'])->get();
        return view('orders.index', compact('bestellingen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materialen = Materiaal::select('id', 'name', 'category_id')->with('category:id,name')->get();
        $sites = Site::select('id', 'locatie')->get();
        return view('orders.create', compact('materialen', 'sites'));
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

        $validated['user_id'] = Auth::user()->id ?? 1;
        $bestelling = Bestelling::create($validated);

        $bestelling->materiaal()->sync($pivotData);
        return redirect()->route('orders.index') ->with('status', 'Bestelling opgeslagen.');
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
