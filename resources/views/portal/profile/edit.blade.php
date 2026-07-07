@extends('layouts.portal')

@section('title', 'Profile Settings')

@section('content')

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card p-6 sm:p-8">
            <h2 class="font-heading font-bold text-lg text-ink-900 mb-6">Profile Information</h2>

            @if ($errors->any() && !$errors->has('current_password'))
                <div class="mb-6 rounded-lg bg-brand-50 border border-brand-200 text-brand-800 text-sm p-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('portal.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="flex items-center gap-4">
                    <span class="w-16 h-16 rounded-full bg-ink-900 text-white flex items-center justify-center font-heading font-bold text-xl overflow-hidden">
                        @if ($user->avatar)
                            <img src="{{ \App\Support\Media::url($user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($user->name, 0, 1) }}
                        @endif
                    </span>
                    <label class="btn-outline !py-2 !px-3 text-xs cursor-pointer">
                        Change Photo
                        <input type="file" name="avatar" accept="image/*" class="hidden">
                    </label>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-ink-600">
                            <input type="checkbox" name="is_overseas" value="1" @checked($user->is_overseas) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Overseas property owner
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Save Changes</button>
            </form>
        </div>

        <div class="card p-6 sm:p-8 h-fit">
            <h2 class="font-heading font-bold text-lg text-ink-900 mb-6">Change Password</h2>

            @if ($errors->has('current_password'))
                <div class="mb-4 rounded-lg bg-brand-50 border border-brand-200 text-brand-800 text-sm p-3">{{ $errors->first('current_password') }}</div>
            @endif

            <form method="POST" action="{{ route('portal.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-sm font-semibold text-ink-700">Current Password</label>
                    <input type="password" name="current_password" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">New Password</label>
                    <input type="password" name="password" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <button type="submit" class="btn-dark w-full justify-center">Update Password</button>
            </form>
        </div>

        <div class="lg:col-span-3 card p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon name="shield-check" class="w-5 h-5" /></span>
                <h2 class="font-heading font-bold text-lg text-ink-900">Two-Factor Authentication</h2>
            </div>
            <p class="text-sm text-ink-500">When enabled, we'll email you a 6-digit code to enter each time you log in, in addition to your password.</p>

            <form method="POST" action="{{ route('portal.profile.security') }}" class="mt-4 flex items-center gap-3">
                @csrf
                @method('PUT')
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="two_factor_enabled" value="1" @checked($user->two_factor_enabled) onchange="this.form.submit()" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500 w-5 h-5">
                    <span class="text-sm font-semibold text-ink-800">{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }} &mdash; toggle to {{ $user->two_factor_enabled ? 'turn off' : 'turn on' }}</span>
                </label>
            </form>
        </div>
    </div>

@endsection
