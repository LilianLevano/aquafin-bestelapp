@php
    $message = session('message', null);
    $success = session('success', null);
    $exception = session('exception', null);
@endphp

@if (isset($success))
    @if ($success)
        <div {{ $attributes->merge(['class' => 'alert alert-success font-medium text-sm text-green-600']) }}>{{ $message }}</div>
    @else
        @if (!$errors->isEmpty())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        @endif

        @if (isset($exception))
            <div class="alert alert-error">
                <span>{{ $exception }}</span>
            </div>
        @endif
    @endif
@endif

