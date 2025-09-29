<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotographReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'photographs_replies';

    protected $fillable = [
        'photograph_id',
        'comment_id',
        'user_id',
        'user_id_reply',
        'reply'
    ];

    /**
     * Relationship with Photograph model
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }

    /**
     * Relationship with PhotographComment model
     */
    public function photographComment()
    {
        return $this->belongsTo(PhotographComment::class, 'comment_id');
    }

    /**
     * Relationship with User model for the user making the reply
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with User model for the user being replied to
     */
    public function replyUser()
    {
        return $this->belongsTo(User::class, 'user_id_reply');
    }
}
