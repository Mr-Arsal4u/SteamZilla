<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Addon;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();
        $addons = Addon::where('is_active', true)->get();
        
        return view('packages', compact('packages', 'addons'));
    }
}

