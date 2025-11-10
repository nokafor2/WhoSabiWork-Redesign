<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, GlobalFunctions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'email', 'password', 'username',
        'phone_number', 'account_status', 'account_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function address() {
        return $this->hasOne('App\Models\Address');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment')->newest();
    }

    public function commentsGiven() {
        return $this->hasMany('App\Models\Comment', 'user_id_comment');
    }

    public function photographs() {
        return $this->hasMany('App\Models\Photograph');
    }

    public function businessCategory() {
        return $this->hasOne('App\Models\BusinessCategory');
    }

    public function artisan() {
        return $this->hasOne('App\Models\Artisan');
    }

    public function product() {
        return $this->hasOne('App\Models\Product');
    }

    public function technicalService() {
        return $this->hasOne('App\Models\TechnicalService');
    }
    
    public function sparePart() {
        return $this->hasOne('App\Models\SparePart');
    }

    public function vehicleCategory() {
        return $this->hasMany('App\Models\VehicleCategory');
    }

    public function carBrand() {
        return $this->hasMany('App\Models\CarBrand');
    }

    public function busBrand() {
        return $this->hasMany('App\Models\BusBrand');
    }

    public function truckBrand() {
        return $this->hasMany('App\Models\TruckBrand');
    }

    public function usersRating() {
        return $this->hasMany('App\Models\UsersRating');
    }

    public function usersAvailability() {
        return $this->hasMany('App\Models\UsersAvailability');
    }

    public function usersAppointment() {
        return $this->hasMany('App\Models\UsersAppointment');
    }

    public function tags() {
        // specify the time stamp to be displayed
        return $this->belongsToMany('App\Models\Tag', 'user_tag')->withTimestamps()->as('tagged');
    }

    public function photographComments() {
        return $this->hasMany('App\Models\PhotographComment');
    }

    public function photographCommentsGiven() {
        return $this->hasMany('App\Models\PhotographComment', 'user_id_comment');
    }

    public function photographCommentsReceived() {
        return $this->hasMany('App\Models\PhotographComment', 'photograph_user_id');
    }

    public function photographReplies() {
        return $this->hasMany('App\Models\PhotographReply');
    }

    public function photographRepliesGiven() {
        return $this->hasMany('App\Models\PhotographReply', 'user_id_reply');
    }

    public function photographRepliesReceived() {
        return $this->hasMany('App\Models\PhotographReply', 'user_id_comment');
    }

    public function photographLikes() {
        return $this->hasMany('App\Models\PhotographLike');
    }

    public function photographLikesGiven() {
        return $this->hasMany('App\Models\PhotographLike', 'user_id');
    }

    public function photographLikesReceived() {
        return $this->hasMany('App\Models\PhotographLike', 'photograph_user_id');
    }

    public function photographDislikes() {
        return $this->hasMany('App\Models\PhotographDislike');
    }

    public function photographDislikesGiven() {
        return $this->hasMany('App\Models\PhotographDislike', 'user_id');
    }

    public function photographDislikesReceived() {
        return $this->hasMany('App\Models\PhotographDislike', 'photograph_user_id');
    }

    public function replies() {
        return $this->hasMany('App\Models\Reply', 'user_id_comment');
    }

    public function repliesReceived() {
        return $this->hasMany('App\Models\Reply', 'user_id_reply');
    }

    public function fullName($id) {
        $user = self::find($id);

        return $user->first_name." ".$user->last_name;
    }

    public function userFullName() {
        return $this->first_name." ".$this->last_name;
    }

    // Add a local query scope to sort the addresses according the newest
    public function scopeNewest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query) {
        // withCount() method will create a variable comments_count in the users object
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeWithMostCommentLastMonth(Builder $query) {
        return $query->withCount(['comments' => function (Builder $query) {
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])->having('comments_count', '>=', 2)
            ->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query) {
        return $query->withCount('comments')->with('tags');
    }

    public function scopeUserCategoryDetails(Builder $query, $businessCategory) {
        $businessCategoryIds = BusinessCategory::where($businessCategory, '=', 1)->select('user_id')->get();

        return $query->whereIn('id', $businessCategoryIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', 'product', 'businessCategory')->get();
    }

    public static function boot() {
        // This global query scope adds query for deleted users
        // static::addGlobalScope(new DeletedAdminScope);

        // Always call the parent method when using event to have access to other events.
        parent::boot();

        // This will add a global query scope to order the list of records according to the date created.
        static::addGlobalScope(new LatestScope);

        // Specify a model in the function
        static::deleting(function (User $user) {
            // This will use the relationship function to delete all the records in the referenced table
            $user->address()->delete();
            $user->comments()->delete();
            $user->BusinessCategory()->delete();
            // Also delete cache after the primary object is deleted
            Cache::tags(['user'])->forget("user-{$user->id}");
        });

        // Use this to update the cache of user
        static::updating(function (User $user) {
            Cache::tags(['user'])->forget("user-{$user->id}");
            Cache::tags(['user'])->forget("mostCommented");
        });

        // Use this event restore functions to restore deleted models with softDeletes
        static::restoring(function (User $user) {
            $user->address()->restore();
            $user->comments()->restore();
            $user->BusinessCategory()->restore();
        });
    }

    // Setting up mutator and accessor method for password.
    // This would automatically Hash the password after validation.
    // public function password(): Attribute {
    //     return Attribute::make(
    //         get: fn ($value) => $value,
    //         set: fn ($value) => Hash::make($value),
    //     );
    // }
}
