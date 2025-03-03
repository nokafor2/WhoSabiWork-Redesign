<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\GlobalFunctions;

class VehicleCategory extends Model
{
    use HasFactory, GlobalFunctions;

    public $table = 'vehicle_categories';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public static $columnsToExclude = ['id', 'user_id', 'business_category', 'created_at', 'updated_at', 'deleted_at'];

    // protected $fillable;
    // public function __construct() {
    //     // $this->fillable = array_diff($this->allTableColumns($this->table), ['id', 'created_at', 'updated_at', 'deleted_at']);
    // }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
