<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchController extends Controller
{
    public function index()
    {
        $merchandises = Merchandise::query()->latest()->get();
        return view('merch.index', compact('merchandises'));
    }
}
