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
     * Relationship with PhotographDislike model - new separate dislikes table
     */
    public function photographDislikes() {
        return $this->hasMany(PhotographDislike::class);
    }

    /**
     * Get all likes for this photograph
     */
    public function photographLikes() {
        return $this->hasMany(PhotographLike::class);
    }

    /**
     * Get active likes where like column = 1
     */
    public function activeLikes() {
        return $this->hasMany(PhotographLike::class)->where('like', 1);
    }

    /**
     * Get cancelled likes where like column = 0
     */
    public function cancelledLikes() {
        return $this->hasMany(PhotographLike::class)->where('like', 0);
    }

    /**
     * Get active dislikes where dislike column = 1
     */
    public function activeDislikes() {
        return $this->hasMany(PhotographDislike::class)->where('dislike', 1);
    }

    /**
     * Get cancelled dislikes where dislike column = 0
     */
    public function cancelledDislikes() {
        return $this->hasMany(PhotographDislike::class)->where('dislike', 0);
    }
}
