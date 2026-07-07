<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Message::whereNull('parent_id')->with('user', 'replies');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $threads = $query->latest()->paginate(15)->withQueryString();

        return view('admin.messages.index', compact('threads'));
    }

    public function show(Message $message): View
    {
        abort_if($message->parent_id, 404);

        $message->load('user', 'replies');

        return view('admin.messages.show', ['thread' => $message]);
    }

    public function reply(Request $request, Message $message): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'user_id' => $message->user_id,
            'parent_id' => $message->id,
            'subject' => 'Re: ' . $message->subject,
            'body' => $validated['body'],
            'sender' => 'staff',
            'status' => 'open',
            'is_read' => false,
        ]);

        AuditLog::record($request->user(), 'Replied to message', $message, "Replied to thread: {$message->subject}");

        return back()->with('success', 'Reply sent.');
    }

    public function resolve(Request $request, Message $message): RedirectResponse
    {
        $message->update(['status' => 'resolved']);

        AuditLog::record($request->user(), 'Resolved message thread', $message, "Resolved: {$message->subject}");

        return back()->with('success', 'Thread marked as resolved.');
    }
}
