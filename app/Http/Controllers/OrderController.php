<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Material;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $orders = Order::with(['user', 'materials', 'site'])
        ->where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->get();

    return view('orders.index', compact('orders'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::select('id', 'name', 'category_id')->with('category:id,name')->get();
        $sites = Site::select('id', 'locatie')->get();
        return view('orders.create', compact('materials', 'sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'materials'     => ['nullable', 'array'],
        'quantity'      => ['nullable', 'array'],
        'delivery_date' => ['required', 'date', 'after:today'],
        'site_id'       => ['required', 'exists:sites,id'],
    ]);

    $pivotData = [];
    foreach ($request->materials ?? [] as $materialId) {
        $pivotData[$materialId] = [
            'quantity' => $request->quantity[$materialId] ?? 0
        ];
    }

    unset($validated['materials'], $validated['quantity']);
    $validated['user_id'] = Auth::id();

    $order = Order::create($validated);
    $order->materials()->sync($pivotData);  // ← was material(), now materials()

    return redirect()->route('orders.index')->with('status', 'Bestelling geplaatst!');
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
