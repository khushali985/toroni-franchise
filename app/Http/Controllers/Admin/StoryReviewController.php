<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\Review;
use App\Models\Franchise;
use Illuminate\Http\Request;

class StoryReviewController extends Controller
{
        public function index(Request $request)
        {
            $franchises = Franchise::all();

            $selectedFranchise = $request->franchise_id;

            $storiesQuery = Story::query();
            $reviewsQuery = Review::query();

            if ($selectedFranchise) {
                $storiesQuery->where('franchise_id', $selectedFranchise);
                $reviewsQuery->where('franchise_id', $selectedFranchise);
            }

            $stories = $storiesQuery->latest()->get();
            $reviews = $reviewsQuery->latest()->get();

            return view('admin.story-review.index', compact(
                'stories',
                'reviews',
                'franchises',
                'selectedFranchise'
            ));
        }

    // ================= STORIES =================

        public function storeStory(Request $request)
        {
            $request->validate([
                'story_img' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                'franchise_id' => 'required|exists:franchises,id'
            ]);

            $imageName = time().'_story.'.$request->story_img->extension();
            $request->story_img->move(public_path('images/story'), $imageName);

            Story::create([
                'story_img' => 'images/story/'.$imageName,
                'franchise_id' => $request->franchise_id
            ]);

            return back()->with('success', 'Story added successfully');
        }

    // ================= REVIEWS =================

        public function storeReview(Request $request)
        {
            $request->validate([
                'cust_name' => 'required',
                'review_text' => 'required',
                'rating' => 'required|integer|min:1|max:5',
                'franchise_id' => 'required|exists:franchises,id'
            ]);

            Review::create([
                'cust_name' => $request->cust_name,
                'review_text' => $request->review_text,
                'rating' => $request->rating,
                'franchise_id' => $request->franchise_id
            ]);

            return back()->with('success', 'Review added successfully');
        }
}