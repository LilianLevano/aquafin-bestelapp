@extends('layouts.app')
@section('title', 'Hulp aanvragen')
@section('content')
    <h1>Aanvragen</h1>

    <div class="status-tabs">
      <a class="btn-primary" href="{{route('admin.help-requests.index', 'open')}}">Open</a>
       <a class="btn-primary" href="{{route('admin.help-requests.index', 'completed')}}">Gesloten</a>
        <a class="btn-primary" href="{{route('admin.help-requests.index', 'all')}}">Alle</a>

    </div>

    <div class="mb">
        <input type="text" id="search-requests" placeholder="Zoek een hulp aanvraag op titel..." autocomplete="off"
               style="margin-bottom: 0; padding: .5rem; width: 100%; position: relative;">
        <ul id="search-suggestions" style=" list-style: none; margin-bottom: 10px; padding: 0; border: 1px solid #ccc; border-top: none; position: absolute; background: white; width: 40%; z-index: 100; display: none; "></ul>
    </div>

    <div id="aanvragen-list">
        @if (count($requests) === 0)
            <p id="empty-msg" class="muted center">No requests found.</p>
        @else

        @foreach ($requests as $request)
            <div class="aanvraag-card"  data-title="{{ $request->title ?? '' }}">
                <div class="aanvraag-header">
                    <div class="aanvraag-title">
                        <label>Titel</label>
                        <input type="text" class="text-field" value="{{ $request->title ?? '' }}" readonly>
                    </div>

                </div>

                <div class="aanvraag-description">
                    <label>Description</label>
                    <textarea class="description-box" rows="5" placeholder="Beschrijf hier je probleem..." readonly>{{ $request->description ?? '' }}</textarea>
                </div>

                <div class="aanvraag-footer">
                    <p><strong>Posted:</strong> {{ isset($request->created_at) ? $request->created_at->format('d/m/Y') : '—' }}</p>
                    <p><strong>Time:</strong> {{ isset($request->created_at) ? $request->created_at->format('H:i') : '—' }}</p>
                    <p><strong>Status:</strong> <span class="status-badge">@if($request->is_completed) Gesloten @else Open @endif</span></p>
                </div>

                @if(!$request->is_completed)<a href="{{route('admin.help-requests.edit',$request->id )}}" class="btn-primary">Answer</a> @endif
                <a class="btn-primary" href="{{route('admin.help-requests.show', $request->id)}}">Meer details</a>

            </div>
        @endforeach
        @endif
    </div>

    <p id="no-results" style="display:none;text-align:center;color:#64748b;padding:16px;">
        No requests found for this status.
    </p>
@endsection

@push('scripts')
    @vite('resources/js/help-requests/help-request-index.js')
@endpush
