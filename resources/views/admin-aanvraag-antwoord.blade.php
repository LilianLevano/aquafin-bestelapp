@extends('layouts.app')

@section('content')
    <div class="aanvraag-card">
        <div class="back-section">
            <a href="/admin/aanvragen" class="btn-primary">← Back</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <h3>Title</h3>
        <input type="text" class="text-field" placeholder="Enter title" value="{{ $aanvraag->title ?? '' }}" readonly>

        <h3>Description</h3>
        <textarea class="description-box" rows="4" readonly>{{ $aanvraag->description ?? '' }}</textarea>

        <h3>Answer to</h3>
        <input type="email" class="text-field" placeholder="test@gmail.com" value="{{ $aanvraag->mail ?? '' }}" readonly>

        <h3>Answer</h3>
        <textarea class="description-box" rows="8" placeholder="Type your answer here..."></textarea>

        <div class="answer-button">
            <button class="btn-primary">Submit Answer</button>
        </div>

    </div>
@endsection
