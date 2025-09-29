<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotographComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'photographs_comments';

    protected $fillable = [
        'photograph_id',
        'user_id',
        'user_id_comment',
        'comment'
    ];

    /**
     * Relationship with Photograph model
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }

    /**
     * Relationship with User model for the user making the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with User model for the user being commented about
     */
    public function commentUser()
    {
        return $this->belongsTo(User::class, 'user_id_comment');
    }

    /**
     * Relationship with PhotographReply model - one comment can have many replies
     */
    public function photographReplies()
    {
        return $this->hasMany(PhotographReply::class, 'comment_id');
    }
}
