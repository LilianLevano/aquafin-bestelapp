@php
    $message = session('message', null);
    $success = session('success', null);
    $exception = session('exception', null);
@endphp

@if (isset($success) && isset($message))
    @if ($success === true)
        <div {{ $attributes->merge(['class' => 'alert alert-success font-medium text-sm text-green-600']) }}>{{ $message }}</div>
    @else
        @if (!$errors->isEmpty())
            <div class="alert alert-error">
                <p>{{ $message }}</p>
                <p>
                    @foreach($errors->all() as $error)
                        <span>&nbsp;&nbsp;&nbsp;&nbsp; - {{ $error }}</span>
                    @endforeach
                </p>

                @if (isset($exception))
                    <p>
                        <span>Volledige foutmelding: {{ $exception }}</span>
                    </p>
                @endif
            </div>
        @endif
    @endif
@endif

