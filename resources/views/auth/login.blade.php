@extends('layouts.app')

@section('title', 'Client Portal Login | GATED Property Services')

@section('content')

    <section class="min-h-[calc(100vh-80px)] bg-ink-950 flex items-center py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center w-full relative">
            <div class="hidden lg:block text-white" data-reveal>
                <span class="section-eyebrow text-brand-500">Client Portal</span>
                <h1 class="mt-3 text-4xl font-heading font-extrabold leading-tight">Access Anytime, Anywhere</h1>
                <p class="mt-4 text-ink-300 max-w-md">Track rent, maintenance, documents and communication with GATED &mdash; all from one secure dashboard.</p>
                <ul class="mt-8 space-y-3">
                    @foreach (['Real-time property updates', 'Financial reports & invoices', 'Maintenance request tracking', 'Secure document storage', 'Direct messaging with our team'] as $item)
                        <li class="flex items-center gap-3 text-sm text-ink-200"><x-icon name="check-circle" class="w-5 h-5 text-brand-500 shrink-0" /> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="card p-8 sm:p-10 w-full max-w-md mx-auto shadow-2xl" data-reveal="zoom" data-reveal-delay="1">
                <h2 class="font-heading font-extrabold text-2xl text-ink-900">Welcome Back</h2>
                <p class="text-sm text-ink-500 mt-1">Log in to your GATED Client Portal.</p>

                @if (session('status'))
                    <div class="mt-4 rounded-lg bg-emerald-50 text-emerald-800 text-sm p-3">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Email Address</label>
                        <div class="input-icon-group mt-1">
                            <x-icon name="envelope" />
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com" class="w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        @error('email') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Password</label>
                        <div class="input-icon-group mt-1">
                            <x-icon name="shield-check" />
                            <input type="password" name="password" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-ink-600">
                            <input type="checkbox" name="remember" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">Log In</button>
                </form>

                <p class="mt-6 text-center text-sm text-ink-500">Don't have an account? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700">Create one</a></p>
                <p class="mt-2 text-center text-xs text-ink-400">Demo login: thetechies804@gmail.com / password</p>
            </div>
        </div>
    </section>

@endsection
