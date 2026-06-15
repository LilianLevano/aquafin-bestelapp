@extends('layouts.app')
@section('title', 'Hulp aanvraag - ' . $request->title)
@section('content')
    <div class="help-request-card">
        <div class="back-section">
            <div style="text-align:left; margin-bottom:20px;">
                <a onclick="history.back()" class="btn-primary">← Keer terug</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <h3>Title</h3>
        <input type="text" class="text-field" placeholder="Enter title" value="{{ $request->title ?? '' }}" readonly>

        <h3>Description</h3>
        <textarea class="description-box" rows="4" readonly>{{ $request->description ?? '' }}</textarea>

        <h3>Answer to</h3>
        <input type="email" class="text-field" placeholder="test@gmail.com" value="{{ $request->email}}" readonly>

            <h3>Answer</h3>
            <textarea class="description-box" readonly rows="8" id="answer" name="answer" placeholder="Type your answer here...">{{$request->answer}}</textarea>

    </div>
@endsection
