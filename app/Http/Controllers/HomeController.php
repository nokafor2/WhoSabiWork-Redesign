<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function photoFeeds() {
        // return view('home.photoFeeds');

        $entrepreneurs = $this->getUserCategoryDetails('artisan');

        return inertia('PhotoFeed/Index', [ 'entrepreneurs' => $entrepreneurs]);
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
