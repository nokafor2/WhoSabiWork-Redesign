<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Photograph extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'filename', 'path', 'size', 'caption', 'photo_type', 'visible'];
    protected $appends = ['src'];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function url() {
        return Storage::url($this->path);
    }

    public function getSrcAttribute() {
        return asset("storage/{$this->path}");
    }

    /**
     * Relationship with PhotographComment model - one photograph can have many comments
     */
    public function photographComments() {
        return $this->hasMany(PhotographComment::class);
    }

    /**
     * Relationship with PhotographReply model - one photograph can have many replies
     */
    public function photographReplies() {
        return $this->hasMany(PhotographReply::class);
    }

    /**
     * Relationship with PhotographLikeDislike model - one photograph can have many likes/dislikes
     */
    public function photographLikesDislikes() {
        return $this->hasMany(PhotographLikeDislike::class);
    }

    /**
     * Get only likes for this photograph
     */
    public function photographLikes() {
        return $this->hasMany(PhotographLikeDislike::class)->whereNotNull('user_id_like');
    }

    /**
     * Get only dislikes for this photograph
     */
    public function photographDislikes() {
        return $this->hasMany(PhotographLikeDislike::class)->whereNotNull('user_id_dislike');
    }
}
