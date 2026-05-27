<?php

namespace App\Http\Controllers;

use App\Models\Documentation;

class DocsController extends Controller
{
    public function index()
    {
        $docs = Documentation::active()->latest()->get();
        return view('docs.index', compact('docs'));
    }
}
