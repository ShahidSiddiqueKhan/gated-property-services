<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::latest()->paginate(15);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $testimonial = Testimonial::create($validated);

        AuditLog::record($request->user(), 'Created testimonial', $testimonial, "Added testimonial from {$testimonial->name}");

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $this->validateTestimonial($request);

        $testimonial->update($validated);

        AuditLog::record($request->user(), 'Updated testimonial', $testimonial, "Updated testimonial from {$testimonial->name}");

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Request $request, Testimonial $testimonial): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted testimonial', null, "Deleted testimonial from {$testimonial->name}");

        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }

    protected function validateTestimonial(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
