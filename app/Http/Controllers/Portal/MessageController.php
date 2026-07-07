<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $threads = Message::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->with(['replies'])
            ->latest()
            ->get();

        Message::where('user_id', $user->id)->where('sender', 'staff')->update(['is_read' => true]);

        return view('portal.messages.index', compact('threads'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:messages,id'],
        ]);

        Message::create([
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'subject' => $validated['subject'] ?? 'New message',
            'body' => $validated['body'],
            'sender' => 'client',
            'status' => 'open',
            'is_read' => true,
        ]);

        return back()->with('success', 'Your message has been sent to the GATED support team.');
    }
}
