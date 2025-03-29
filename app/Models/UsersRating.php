<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersRating extends Model
{
    /** @use HasFactory<\Database\Factories\UsersRatingFactory> */
    use HasFactory, GlobalFunctions;

    public $table = 'users_ratings';

    protected $fillable = ['user_id', 'rating', 'rated_by_user'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
