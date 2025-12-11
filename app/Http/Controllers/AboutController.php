<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $pageTitle = PageContent::getContent('about', 'main', 'title', 'About SteamZilla');
        $mainDescription = PageContent::getContent('about', 'main', 'description', '');
        $mission = PageContent::getContent('about', 'main', 'mission', '');
        
        // Get all about page content sections
        $aboutContents = PageContent::where('page', 'about')
            ->orderBy('section')
            ->orderBy('order')
            ->get()
            ->groupBy('section');
        
        return view('about', compact('pageTitle', 'mainDescription', 'mission', 'aboutContents'));
    }
}
