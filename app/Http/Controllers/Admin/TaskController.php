<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Property;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with('user', 'property');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $properties = Property::orderBy('title')->get();

        return view('admin.tasks.create', compact('clients', 'properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task = Task::create([...$validated, 'status' => 'pending']);

        AuditLog::record($request->user(), 'Created task', $task, "Created task: {$task->title}");

        return redirect()->route('admin.tasks.index')->with('success', 'Task created.');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
        ]);

        $task->update($validated);

        AuditLog::record($request->user(), 'Updated task', $task, "Set {$task->title} to {$validated['status']}");

        return back()->with('success', 'Task updated.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted task', null, "Deleted task: {$task->title}");

        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}
