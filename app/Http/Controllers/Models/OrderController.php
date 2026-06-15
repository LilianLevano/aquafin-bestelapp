<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\WebController;
use App\Models\Order;
use App\Models\Material;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Override;

class OrderController extends WebController
{
    /**
     * Display a listing of the resource.
     */
    #[Override]
    public function index(): View
    {
        $date = request()->query('datum', Carbon::today()->toDateString());

        try {
            $orders = Order::with(['user', 'materials'])
                ->whereDate('created_at', $date)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($order) {
                    $fullName = trim(($order->user?->first_name ?? '') . ' ' . ($order->user?->last_name ?? ''));
                    $items = $order->materials->pluck('name')->implode(', ');
                    return (object) [
                        'id' => $order->id,
                        'geplaatst_door' => $fullName ?: '—',
                        'datum' => $order->created_at,
                        'items' => $items ?: '—',
                    ];
                });

            return view('orders.index', compact('orders'))
                ->with('success', true);
        } catch (\Exception $e) {
            Log::error('Fout bij ophalen bestellingen: ' . $e->getMessage());
            $dateFormatted = Carbon::parse($date)->format('d/m/Y');

            return view('orders.index')
                ->with('success', false)
                ->with('message', "Er ging iets mis met het ophalen van de bestellingen van {$dateFormatted}");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Override]
    public function create(): View
    {
        $materials = Material::select(['id', 'name', 'category_id'])->with('category:id,name')->get();
        $sites = Site::select(['id', 'description'])->get();
        return view('orders.create', compact('materials', 'sites'));
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
            },
            [
                200 => [
                    'message' => 'Jouw bestelling werd gestuurd!',
                    'route' => url()->previous()],
                422 => [
                    'message' => 'Jouw bestelling werd niet doorgestuurd, probeer het opnieuw later.',
                    'route' => url()->previous()],
                500 => [
                    'message' => 'Jouw bestelling werd niet doorgestuurd, probeer het opnieuw later.',
                    'route' => url()->previous()]
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    #[Override]
    public function show($id): View
    {
        try {
            $order = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select(
                    'orders.id',
                    'orders.created_at as datum',
                    'orders.delivery_date',
                    DB::raw("(users.first_name || ' ' || users.last_name) as technieker_naam"),
                    'users.email as technieker_email'
                )
                ->where('orders.id', $id)
                ->first();

            if (!$order) {
                return view('orders.show')
                    ->with('success', false)
                    ->with('message', "Bestelling #{$id} werd niet gevonden.");
            }

            $materials = DB::table('order_materials')
                ->join('materials', 'order_materials.material_id', '=', 'materials.id')
                ->join('categories', 'materials.category_id', '=', 'categories.id')
                ->select(
                    'materials.id as materiaal_id',
                    'materials.name as naam',
                    'categories.name as categorie',
                    'order_materials.quantity as hoeveelheid'
                )
                ->where('order_materials.order_id', $id)
                ->get();

            return view('orders.show', compact(['order', 'materials']))
                ->with('success', true);
        } catch (\Exception $e) {
            Log::error('Fout bij ophalen bestelling detail: ' . $e->getMessage());

            return view('orders.index')
                ->with('success', false)
                ->with('message', 'Er ging iets mis met het ophalen van de bestellingsdetails.');
        }
    }
}
