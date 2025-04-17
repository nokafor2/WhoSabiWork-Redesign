<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersAvailability extends Model
{
    /** @use HasFactory<\Database\Factories\UsersAvailabilityFactory> */
    use HasFactory, GlobalFunctions, SoftDeletes;

    protected $fillable = ['user_id', 'date_available', 'eight_AM', 'eight_Thirty_AM', 
    'nine_AM', 'nine_Thirty_AM', 'ten_AM', 'ten_Thirty_AM', 'eleven_AM', 'eleven_Thirty_AM', 
    'twelve_PM', 'twelve_Thirty_PM', 'one_PM', 'one_Thirty_PM', 'two_PM', 'two_Thirty_PM', 
    'three_PM', 'three_Thirty_PM', 'four_PM', 'four_Thirty_PM', 'five_PM', 'five_Thirty_PM', 
    'six_PM', 'six_Thirty_PM', 'seven_PM', 'seven_Thirty_PM'];

    public static $columnsToExclude = ['id', 'user_id', 'date_available', 'created_at', 'updated_at', 'deleted_at'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
