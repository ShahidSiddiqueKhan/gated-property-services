@extends('layouts.portal')

@section('title', 'Redirecting to JazzCash')

@section('content')

    <div class="max-w-md mx-auto text-center py-16">
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-5">
            <x-icon name="banknotes" class="w-7 h-7" />
        </div>
        <h2 class="font-heading font-bold text-xl text-ink-900">Redirecting you to JazzCash&hellip;</h2>
        <p class="mt-2 text-sm text-ink-500">Please don't close this window. You'll be taken to JazzCash's secure payment page automatically.</p>

        <div class="mt-6 flex items-center justify-center gap-2 text-ink-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.15s"></span>
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.3s"></span>
        </div>

        <form id="jazzcash-redirect-form" action="{{ $endpoint }}" method="POST">
            @foreach ($fields as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>

        <noscript>
            <p class="mt-6 text-sm text-ink-600">JavaScript is disabled in your browser. Click below to continue to JazzCash.</p>
            <button type="submit" form="jazzcash-redirect-form" class="btn-primary mt-3">Continue to JazzCash</button>
        </noscript>
    </div>

    <script>
        document.getElementById('jazzcash-redirect-form').submit();
    </script>

@endsection
