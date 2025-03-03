<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;
	
    protected $fillable = ['user_id', 'address_line_1', 'address_line_2', 'address_line_3', 'town', 'state'];
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function fullAddress() {
        $fullAddress = "";
		$addressLine1 = ucFirst(trim($this->address_line_1));
		$addressLine2 = trim($this->address_line_2);
		$addressLine3 = trim($this->address_line_3);
		$town = ucfirst(trim($this->town));
		$state = ucfirst(trim($this->state));

		if (isset($addressLine1) && ($addressLine1 !== "")) {
			$fullAddress .= $addressLine1;
		}
		if (isset($addressLine2) && ($addressLine2 !== "")) {
			$fullAddress .= ", ".$addressLine2;
		}
		if (isset($addressLine3) && ($addressLine3 !== "")) {
			$fullAddress .= ", ".$addressLine3;
		}
		if (isset($town) && ($town !== "")) {
			$fullAddress .= ", ".$town;
		}
		if (isset($state) && ($state !== "")) {
			$fullAddress .= ", ".$state;
		}

		return $fullAddress;
    }

    // Add a local query scope to sort the addresses according the newest
    public function scopeNewest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot() {
        // This global query scope adds query for deleted users
        // static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // Add a global query scope
        // static::addGlobalScope(new LatestScope);
        // static::scopes(['newest']);
    }
}
