<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\GlobalFunctions;

class SparePart extends Model
{
    use HasFactory, GlobalFunctions;

    public $table = 'spare_parts';

    protected $fillable = [
        'id', 'user_id', 'engine', 'engine_part', 'tyre', 'mechanical_part', 'electrical_part', 
        'door', 'side_mirror', 'body_part', 'light', 'widnscreen', 'chair', 'upholstery_part', 
        'lubricant', 'dashboard', 'bumper', 'air_conditioning_part', 'compressor', 'condenser', 
        'radiator', 'transmission', 'exhaust_part', 'steering_system', 'steering_rack', 'steering', 
        'shock_absorber', 'shock_spring', 'wheel', 'brake_disk', 'radio_system', 'fan', 'chasis', 
        'fuel_tank', 'sensor', 'speaker_system', 'seat_belt', 'windshield_wiper', 'key', 'motor', 
        'jack', 'horn', 'belt', 'chain', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public static $columnsToExclude = ['id', 'user_id',  'created_at', 'updated_at', 'deleted_at'];
    // protected $fillable;
    // public function __construct() {
    //     // $this->fillable = array_diff($this->allTableColumns($this->table), ['id', 'created_at', 'updated_at', 'deleted_at']);
    // }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
