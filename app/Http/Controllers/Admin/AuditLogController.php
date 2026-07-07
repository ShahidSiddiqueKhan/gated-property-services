<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('action', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}
