<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceImage;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceUpdate;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $requests = MaintenanceRequest::where('user_id', $request->user()->id)
            ->with('property')
            ->latest()
            ->paginate(8);

        return view('portal.maintenance.index', compact('requests'));
    }

    public function create(Request $request): View
    {
        $properties = Property::where('user_id', $request->user()->id)->get();

        return view('portal.maintenance.create', compact('properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:plumbing,electrical,structural,appliance,pest_control,painting,other'],
            'priority' => ['required', 'in:low,medium,high,emergency'],
            'description' => ['required', 'string'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $property = Property::findOrFail($validated['property_id']);
        abort_unless($property->user_id === $request->user()->id, 403);

        $maintenanceRequest = MaintenanceRequest::create([
            'ticket_no' => 'MNT-' . strtoupper(Str::random(6)),
            'property_id' => $property->id,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'submitted',
        ]);

        MaintenanceUpdate::create([
            'maintenance_request_id' => $maintenanceRequest->id,
            'status' => 'submitted',
            'note' => 'Request submitted by client.',
            'created_by' => $request->user()->name,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('maintenance/' . $maintenanceRequest->id, 'public');
                MaintenanceImage::create([
                    'maintenance_request_id' => $maintenanceRequest->id,
                    'path' => $path,
                    'uploaded_by' => 'client',
                ]);
            }
        }

        return redirect()->route('portal.maintenance.show', $maintenanceRequest)
            ->with('success', 'Your maintenance request has been submitted. Ticket #' . $maintenanceRequest->ticket_no);
    }

    public function show(Request $request, MaintenanceRequest $maintenanceRequest): View
    {
        abort_unless($maintenanceRequest->user_id === $request->user()->id, 403);

        $maintenanceRequest->load('images', 'updates', 'property');

        return view('portal.maintenance.show', compact('maintenanceRequest'));
    }
}
