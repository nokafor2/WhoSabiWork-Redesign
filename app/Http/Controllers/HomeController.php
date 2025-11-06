<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    
    use GlobalFunctions;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        // dd(Auth::user());
        // dd(Auth::id());
        // dd(Auth::check());
        // return view('home.index');

        return inertia('Index/Index');
    }
    
    //
    public function home() {
        
    }

    public function photoFeeds(Request $request) {
        // Get current page from request, default to 1
        $page = $request->get('page', 1);
        $perPage = 15;
        
        // Cache key includes the page number and authenticated user ID for personalized caching
        $authUserId = Auth::id() ?? 'guest';
        $cacheKey = "photo_feed_page_{$page}_user_{$authUserId}";
        
        // Cache the photo feed data for 10 minutes (600 seconds)
        $photoFeedData = Cache::remember($cacheKey, 600, function () use ($perPage) {
            return $this->photoFeedData($perPage);
        });

        return inertia('PhotoFeed/Index', [
            'photoFeedData' => $photoFeedData
        ]);
    }

    public function mobileMarket() {
        // Get mobile market sellers
        $businessCategory = 'seller';
        $entrepreneurs = User::userCategoryDetails($businessCategory);

        return view('home.mobileMarket', 
            [
                'businessCategory' => $businessCategory,
                'entrepreneurs' => $entrepreneurs
            ]
        );
    }

    public function demoArtisan() {
        return view('home.artisans');
    }

    public function artisans() {
        return view('customers.home', [
            // 'users' => User::withCount('comments')->with('user')->get(),
            'users' => User::withCount('comments')->get(),
            'mostCommented' => User::mostCommented()->take(5)->get(),
            'mostActiveLastMonth' => User::withMostCommentLastMonth()->take(5)->get(),
        ]);
    }

    public function services() {
        return view('home.services');
    }

    public function spareParts() {
        $businessCategory = 'spare_part_seller';
        // Get mobile market sellers
        $entrepreneurs = User::userCategoryDetails($businessCategory);
        
        return view('home.spareParts', 
            [
                'businessCategory' => $businessCategory,
                'entrepreneurs' => $entrepreneurs
            ]
        );
    }

    public function contactUs() {
        return view('home.contactUs');
    }

    // created in lesson 140 for authorization of routes
    public function secret() {
        return view('home.secret');
    }
}
