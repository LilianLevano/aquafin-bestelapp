<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Material;
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
        $orders = Order::with(['user', 'materials', 'site'])->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::select('id', 'name', 'category_id')->with('category:id,name')->get();
        $sites = Site::select('id', 'description')->get();
        return view('orders.create', compact('materials', 'sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materials' => ['nullable','array'],
            'quantity' => ['nullable','array'],
            'delivery_date' => ['required', 'date', 'after:today'],
            'site_id' => ['required', 'exists:sites,id'],
        ]);

        $pivotData = [];

        foreach ($request->materials ?? [] as $materialId) {
            $pivotData[$materialId] = [
                'quantity' => $request->quantity[$materialId]
            ];
        }

        unset($validated['materials']);
        unset($validated['quantity']);

        $validated['user_id'] = Auth::user()->id ?? 1;
        $order = Order::create($validated);

        $order->materials()->sync($pivotData);
        return back()->with('status', 'Order saved');
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
