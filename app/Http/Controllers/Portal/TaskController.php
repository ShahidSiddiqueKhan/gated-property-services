<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = Task::where('user_id', $request->user()->id)
            ->with('property')
            ->orderByRaw("field(status, 'in_progress', 'pending', 'completed')")
            ->latest()
            ->get();

        return view('portal.tasks.index', compact('tasks'));
    }
}
