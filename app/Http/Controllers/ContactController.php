<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['nullable', 'in:general,consultation,callback'],
            'preferred_at' => ['nullable', 'date'],
        ]);

        ContactSubmission::create([
            ...$validated,
            'type' => $validated['type'] ?? 'general',
        ]);

        $message = $validated['type'] === 'consultation'
            ? 'Your video consultation request has been received! Our team will confirm your slot by email shortly.'
            : 'Thanks for reaching out! A member of our team will respond within one business day.';

        return back()->with('success', $message);
    }
}
