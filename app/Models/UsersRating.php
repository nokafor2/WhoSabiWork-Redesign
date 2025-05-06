<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersRating extends Model
{
    /** @use HasFactory<\Database\Factories\UsersRatingFactory> */
    use HasFactory, GlobalFunctions, SoftDeletes;

    public $table = 'users_ratings';

    protected $fillable = ['user_id', 'rating', 'rated_by_user'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function ratingCount($userRatings) {
        return $userRatings->count();
    }

    public function avgRating($userRatings) {
        $ratingCount = $userRatings->count();
        $ratingSum = 0;
        $userRatings->each(function ($userRating) use (&$ratingSum) {
            $ratingSum += $userRating->rating;
        });
        $avgRating = round($ratingSum / $ratingCount);

        return $avgRating;
    }
}
