<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'user_id_comment', 'body'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function commentUser() {
        return $this->belongsTo('App\Models\User', 'user_id_comment');
    }

    /**
     * Relationship with Reply model - one comment can have many replies
     */
    public function replies() {
        return $this->hasMany(Reply::class);
    }

    // Add a local query scope to sort by newest
    public function scopeNewest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot() {
        // This global query scope adds query for deleted users
        // static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // static::addGlobalScope(new LatestScope);

        // Use this to update the cache of user
        static::creating(function (Comment $comment) {
            Cache::tags(['user'])->forget("user-{$comment->user_id}");
            Cache::tags(['user'])->forget("user-most-commented");
        });
    }
}
