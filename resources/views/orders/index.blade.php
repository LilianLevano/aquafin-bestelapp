@extends('layouts.app')
@section('title', 'Mijn Bestellingen')

@section('content')
<div style="padding: 2rem; max-width: 1100px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h1 mb-0">Mijn Bestellingen</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            + Nieuwe bestelling
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Geplaatst door</th>
                        <th>Items</th>
                        <th>Locatie</th>
                        <th>Leverdatum</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                    
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="font-monospace text-muted small">#{{ $order->id }}</td>
                            <td class="fw-medium">
                                {{ ($order->user->first_name ?? '') . ' ' . ($order->user->last_name ?? '') }}
                            </td>
                            <td class="text-muted small">
                                {{ $order->materials->take(3)->map(fn($m) => $m->name . ' (x' . $m->pivot->quantity . ')')->implode(', ') }}
                                {{ $order->materials->count() > 3 ? ', …' : '' }}
                            </td>
                            <td>{{  $order->site->description ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</td>
                           <td>
                                @if(\Carbon\Carbon::parse($order->delivery_date)->isPast())
                                    <span class="badge bg-success">Geleverd</span>
                                @else
                                    <span class="badge bg-warning text-dark">Aan het leveren</span>
                                @endif
                            </td>
                            <td>
                                @if(!\Carbon\Carbon::parse($order->delivery_date)->isPast())
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                          onsubmit="return confirm('Bent u zeker dat u bestelling #{{ $order->id }} wilt annuleren?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Annuleren
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted fst-italic py-5">
                                U heeft nog geen bestellingen geplaatst.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection