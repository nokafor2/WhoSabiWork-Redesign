<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotographLike extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'photographs_likes';

    protected $fillable = [
        'photograph_id',
        'photograph_user_id',
        'user_id',
        'like'
    ];

    /**
     * Relationship with Photograph model
     */
    public function photograph()
    {
        return $this->belongsTo(Photograph::class);
    }

    /**
     * Relationship with User model (user who liked the photograph)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with User model (owner of the photograph)
     */
    public function photographOwner()
    {
        return $this->belongsTo(User::class, 'photograph_user_id');
    }
}
