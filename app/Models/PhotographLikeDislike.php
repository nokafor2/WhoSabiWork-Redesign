<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotographLikeDislike extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'photograph_likes_dislikes';

    protected $fillable = [
        'photograph_id',
        'user_id',
        'user_id_like',
        'user_id_dislike'
    ];

    /**
     * Relationship with Photograph model
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }

    /**
     * Relationship with User model for the user performing the action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with User model for the user who liked
     */
    public function likeUser()
    {
        return $this->belongsTo(User::class, 'user_id_like');
    }

    /**
     * Relationship with User model for the user who disliked
     */
    public function dislikeUser()
    {
        return $this->belongsTo(User::class, 'user_id_dislike');
    }

    /**
     * Check if this record represents a like
     */
    public function isLike()
    {
        return !is_null($this->user_id_like) && is_null($this->user_id_dislike);
    }

    /**
     * Check if this record represents a dislike
     */
    public function isDislike()
    {
        return !is_null($this->user_id_dislike) && is_null($this->user_id_like);
    }
}
