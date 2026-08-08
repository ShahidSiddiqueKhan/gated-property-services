@extends('layouts.app')

@section('title', 'Verify Your Identity | GATED Property Services')

@section('content')

    <section class="min-h-[calc(100vh-80px)] bg-ink-950 flex items-center py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-md mx-auto px-6 w-full relative">
            <div class="card p-8 sm:p-10 shadow-2xl" data-reveal="zoom">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                    <x-icon name="lock-closed" class="w-6 h-6" />
                </div>
                <h2 class="font-heading font-extrabold text-2xl text-ink-900">Two-Factor Verification</h2>
                <p class="text-sm text-ink-500 mt-1">We've emailed a 6-digit code to <span class="font-semibold text-ink-700">{{ $email }}</span>. Enter it below to continue.</p>

                @if (session('success'))
                    <div class="mt-4 rounded-lg bg-emerald-50 text-emerald-800 text-sm p-3">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Verification Code</label>
                        <input type="text" name="code" inputmode="numeric" maxlength="6" autofocus required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500 text-center tracking-[0.5em] font-heading font-bold text-lg">
                        @error('code') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">Verify &amp; Continue</button>
                </form>

                <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-center text-sm font-semibold text-brand-600 hover:text-brand-700">Resend Code</button>
                </form>

                <p class="mt-4 text-xs text-ink-400 text-center">Note: in local development, verification codes are written to your Laravel log file rather than a real inbox, unless you've configured a mail provider.</p>
            </div>
        </div>
    </section>

@endsection
