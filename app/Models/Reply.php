<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'comment_id',
        'user_id_comment',
        'user_id_reply',
        'body'
    ];

    /**
     * Relationship with Comment model
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Relationship with User model for the user being commented about
     */
    public function commentUser()
    {
        return $this->belongsTo(User::class, 'user_id_comment');
    }

    /**
     * Relationship with User model for the user making the reply
     */
    public function replyUser()
    {
        return $this->belongsTo(User::class, 'user_id_reply');
    }
}
