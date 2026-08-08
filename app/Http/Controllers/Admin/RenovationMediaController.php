<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RenovationMedia;
use App\Models\RenovationProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RenovationMediaController extends Controller
{
    public function store(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:photo,video,invoice'],
            'phase' => ['nullable', 'in:before,progress,after'],
            'caption' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        $path = $request->file('file')->store('renovations/' . $renovation->id, 'public');

        RenovationMedia::create([
            'renovation_project_id' => $renovation->id,
            'type' => $validated['type'],
            'phase' => $validated['phase'] ?? null,
            'path' => $path,
            'caption' => $validated['caption'] ?? null,
        ]);

        AuditLog::record($request->user(), 'Uploaded renovation media', $renovation, "Uploaded {$validated['type']} to {$renovation->title}");

        return back()->with('success', 'File uploaded.');
    }

    public function destroy(Request $request, RenovationProject $renovation, RenovationMedia $media): RedirectResponse
    {
        $media->delete();

        AuditLog::record($request->user(), 'Deleted renovation media', $renovation, "Deleted media from {$renovation->title}");

        return back()->with('success', 'File removed.');
    }
}
