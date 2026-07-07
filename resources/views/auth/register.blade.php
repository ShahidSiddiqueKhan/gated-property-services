@extends('layouts.app')

@section('title', 'Create Your Client Account | GATED Property Services')

@section('content')

    <section class="min-h-[calc(100vh-80px)] bg-ink-950 flex items-center py-16">
        <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center w-full">
            <div class="hidden lg:block text-white">
                <span class="section-eyebrow text-brand-500">Join GATED</span>
                <h1 class="mt-3 text-4xl font-heading font-extrabold leading-tight">Create Your Client Account</h1>
                <p class="mt-4 text-ink-300 max-w-md">Set up your account to register properties, track rent, and communicate with our team &mdash; all in one place.</p>
            </div>

            <div class="card p-8 sm:p-10 w-full max-w-md mx-auto">
                <h2 class="font-heading font-extrabold text-2xl text-ink-900">Create Account</h2>
                <p class="text-sm text-ink-500 mt-1">Get access to the GATED Client Portal.</p>

                <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        @error('name') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        @error('email') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Password</label>
                        <input type="password" name="password" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        @error('password') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink-600">
                        <input type="checkbox" name="is_overseas" value="1" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> I am an overseas property owner
                    </label>
                    <button type="submit" class="btn-primary w-full justify-center">Create Account</button>
                </form>

                <p class="mt-6 text-center text-sm text-ink-500">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">Log in</a></p>
            </div>
        </div>
    </section>

@endsection
