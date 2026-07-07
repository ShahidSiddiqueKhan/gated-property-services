<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $properties = Property::where('user_id', $request->user()->id)
            ->with('coverImage', 'activeLease')
            ->latest()
            ->paginate(9);

        return view('portal.properties.index', compact('properties'));
    }

    public function show(Request $request, Property $property): View
    {
        abort_unless($property->user_id === $request->user()->id, 403);

        $property->load('images', 'leases', 'payments', 'maintenanceRequests', 'documents');

        return view('portal.properties.show', compact('property'));
    }
}
