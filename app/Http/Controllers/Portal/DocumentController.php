<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Document::where('user_id', $request->user()->id)->with('property');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('portal.documents.index', compact('documents'));
    }
}
