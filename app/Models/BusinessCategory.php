<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCategory extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'artisan', 'technician', 'seller', 'spare_part_seller', 
        'business_name', 'business_description', 'business_page', 
        'views', 'advertise'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    // Add a local query scope to sort the addresses according the newest
    public function scopeNewest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function userBusinessCategory($id) {
        $userbusinessDetails = self::find($id);
        $selectedBusinesses = array();
        
        if (isset($userbusinessDetails->artisan) && ($userbusinessDetails->artisan == 1)) {
            $selectedBusinesses[] = 'artisan';
        }
        if (isset($userbusinessDetails->seller) && ($userbusinessDetails->seller == 1)) {
            $selectedBusinesses[] = 'seller';
        }
        if (isset($userbusinessDetails->technician) && ($userbusinessDetails->technician == 1)) {
            $selectedBusinesses[] = 'technician';
        }
        if (isset($userbusinessDetails->spare_part_seller) && ($userbusinessDetails->spare_part_seller == 1)) {
            $selectedBusinesses[] = 'spare_part_seller';
        }

        return $selectedBusinesses;
    }

    public static function boot() {
        // This global query scope adds query for deleted users
        // static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // static::addGlobalScope(new LatestScope);
    }
}
