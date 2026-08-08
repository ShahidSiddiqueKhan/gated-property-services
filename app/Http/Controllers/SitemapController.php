<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Property;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.8'],
            ['loc' => route('services.index'), 'priority' => '0.8'],
            ['loc' => route('properties.index'), 'priority' => '0.8'],
            ['loc' => route('promotions.index'), 'priority' => '0.6'],
            ['loc' => route('property-registration.create'), 'priority' => '0.7'],
            ['loc' => route('blog.index'), 'priority' => '0.6'],
            ['loc' => route('contact.show'), 'priority' => '0.6'],
            ['loc' => route('faq'), 'priority' => '0.5'],
        ]);

        foreach (Service::where('is_active', true)->get() as $service) {
            $urls->push(['loc' => route('services.show', $service), 'priority' => '0.6']);
        }

        foreach (Property::published()->get() as $property) {
            $urls->push(['loc' => route('properties.show', $property), 'priority' => '0.6']);
        }

        foreach (BlogPost::published()->get() as $post) {
            $urls->push(['loc' => route('blog.show', $post), 'priority' => '0.5']);
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
