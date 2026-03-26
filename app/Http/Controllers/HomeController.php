<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch all stories (latest first)
        $stories = Story::latest()->get();

        // Fetch latest 5 reviews (read-only)
        $reviews = Review::latest()->take(5)->get();

        return view('pages.home', compact('stories', 'reviews'));
    }
}
