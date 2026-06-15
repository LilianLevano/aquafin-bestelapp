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
        $sites = Site::select('id', 'description')->get();
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
         'site_id'       => ['required', 'in:1,2,3,4'],
    ]);

        $pivotData = [];

        foreach ($request->materials ?? [] as $materialId) {
            $qty = $request->quantity[$materialId] ?? 0;

            if ($qty <= 0) continue;

            $pivotData[$materialId] = [
                'quantity' => $qty
            ];
        }

    unset($validated['materials'], $validated['quantity']);
    $validated['user_id'] = Auth::id();

    $order = Order::create($validated);
    $order->materials()->sync($pivotData);  // ← was material(), now materials()

        return back()->with('status', 'Order saved');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['materials', 'site'])->find($id);
        return view('orders.detail', compact('order'));
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

    public function destroy(string $id)
{
    $order = Order::findOrFail($id);

    // Technieker can only cancel their own orders
    if (Auth::user()->role->name === 'Technieker' && $order->user_id !== Auth::id()) {
        return redirect()->route('orders.index')->with('error', 'U heeft geen toegang om deze bestelling te annuleren.');
    }

    $order->delete(); // soft delete because the migration has softDeletes

    return redirect()->back()->with('status', 'Bestelling geannuleerd.');
}
}
