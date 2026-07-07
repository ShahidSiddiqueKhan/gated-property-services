<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('2fa_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.two-factor', ['email' => $user->email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = User::find($userId);

        if (! $user || $user->two_factor_code !== $validated['code'] || now()->greaterThan($user->two_factor_expires_at)) {
            return back()->withErrors(['code' => 'That code is invalid or has expired.']);
        }

        $user->forceFill(['two_factor_code' => null, 'two_factor_expires_at' => null])->save();

        $remember = (bool) $request->session()->pull('2fa_remember', false);
        $request->session()->forget('2fa_user_id');

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect((new AuthController)->redirectPathForRole($user));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::raw("Your new GATED Property Services verification code is: {$code}\n\nThis code expires in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)->subject('Your GATED Property Services verification code');
        });

        return back()->with('success', 'A new code has been sent to your email.');
    }
}
