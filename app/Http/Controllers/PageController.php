<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Testimonial;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $testimonials = Testimonial::where('is_featured', true)->latest()->take(3)->get();

        return view('about', compact('testimonials'));
    }

    public function promotions(): View
    {
        $promotions = Promotion::active()->orderBy('valid_until')->paginate(9);

        return view('promotions.index', compact('promotions'));
    }

    public function faq(): View
    {
        return view('faq');
    }

    public function privacy(): View
    {
        return view('legal.privacy');
    }

    public function terms(): View
    {
        return view('legal.terms');
    }
}
