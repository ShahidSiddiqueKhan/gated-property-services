<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Promotion;
use App\Models\Property;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProperties = Property::published()->featured()->with('coverImage')->latest()->take(6)->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->take(8)->get();
        $testimonials = Testimonial::where('is_featured', true)->latest()->take(4)->get();
        $posts = BlogPost::published()->latest('published_at')->take(3)->get();
        $promotion = Promotion::active()->latest()->first();

        $stats = [
            'properties_managed' => Property::count() + 493, // presented as a rounded company milestone
            'client_satisfaction' => 98,
            'years_experience' => 7,
            'support_available' => '24/7',
        ];

        return view('home', compact('featuredProperties', 'services', 'testimonials', 'posts', 'stats', 'promotion'));
    }
}
