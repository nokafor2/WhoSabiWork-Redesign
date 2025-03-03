<?php

namespace App\Http\ViewComposers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer {
    public function compose(View $view) {
        // Setting up cache
        // The remeber method take 3 arguments, the key, time to store the cache and a call back
        $mostCommented = Cache::tags(['user'])->remember('user-most-commented', now()->addMinutes(10), function() {
            return User::mostCommented()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('user-most-active-last-month', now()->addMinutes(10), function() {
            return User::withMostCommentLastMonth()->take(5)->get();
        });

        $view->with('mostCommented', $mostCommented);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);
    }
}