<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->two_factor_enabled) {
            return $this->challengeTwoFactor($request, $user, $remember);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathForRole($user));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_overseas' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'is_overseas' => $request->boolean('is_overseas'),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Log the user back out, generate a one-time code, email it, and stash
     * their pending login in the session until they verify it.
     */
    protected function challengeTwoFactor(Request $request, User $user, bool $remember): RedirectResponse
    {
        Auth::logout();

        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::raw("Your GATED Property Services verification code is: {$code}\n\nThis code expires in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)->subject('Your GATED Property Services verification code');
        });

        $request->session()->put('2fa_user_id', $user->id);
        $request->session()->put('2fa_remember', $remember);

        return redirect()->route('two-factor.show');
    }

    public function redirectPathForRole(User $user): string
    {
        return $user->isAdmin() ? route('admin.dashboard') : route('portal.dashboard');
    }
}
