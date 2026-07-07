<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Property::published()->with('coverImage');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->string('listing_type'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->string('city'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('area_location', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest('published_at')->paginate(9)->withQueryString();

        $cities = Property::published()->whereNotNull('city')->distinct()->pluck('city');

        return view('properties.index', compact('properties', 'cities'));
    }

    public function show(Property $property): View
    {
        abort_unless($property->published_at, 404);

        $property->load('images', 'owner');

        $related = Property::published()
            ->where('id', '!=', $property->id)
            ->where('category', $property->category)
            ->with('coverImage')
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'related'));
    }
}
