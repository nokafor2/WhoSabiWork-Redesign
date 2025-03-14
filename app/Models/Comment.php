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
    protected $fillable = ['user_id', 'business_id', 'body'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Add a local query scope to sort the addresses according the newest
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
