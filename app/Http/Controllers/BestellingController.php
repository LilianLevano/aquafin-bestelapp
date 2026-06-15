<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BestellingController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->wantsJson()) {
            return view('orders.manager-index');
        }

        $datum = $request->query('datum', Carbon::today()->toDateString());

        try {
            $bestellingen = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select(
                    'orders.id',
                    DB::raw("(users.first_name || ' ' || users.last_name) as geplaatst_door"),
                    'orders.created_at as datum'
                )
                ->whereDate('orders.created_at', $datum)
                ->orderByDesc('orders.created_at')
                ->get()
                ->map(function ($order) {
                    $items = DB::table('order_materials')
                        ->join('materials', 'order_materials.material_id', '=', 'materials.id')
                        ->where('order_materials.order_id', $order->id)
                        ->pluck('materials.name')
                        ->implode(', ');

                    $order->items = $items ?: '—';
                    return $order;
                });

            return response()->json([
                'success' => true,
                'data'    => $bestellingen,
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij ophalen bestellingen: ' . $e->getMessage());
            $datumFormatted = Carbon::parse($datum)->format('d/m/Y');

            return response()->json([
                'success' => false,
                'message' => "Er ging iets mis met het ophalen van de bestellingen van {$datumFormatted}",
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        if (!$request->wantsJson()) {
            return view('orders.manager-detail');
        }

        try {
            $bestelling = DB::table('orders')
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

            if (!$bestelling) {
                return response()->json([
                    'success' => false,
                    'message' => "Bestelling #{$id} werd niet gevonden.",
                ], 404);
            }

            $materialen = DB::table('order_materials')
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

            return response()->json([
                'success'    => true,
                'bestelling' => $bestelling,
                'materialen' => $materialen,
            ]);

        } catch (\Exception $e) {
            Log::error('Fout bij ophalen bestelling detail: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Er ging iets mis met het ophalen van de bestellingsdetails.',
            ], 500);
        }
    }
}
