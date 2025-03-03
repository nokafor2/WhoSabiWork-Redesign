<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\GlobalFunctions;

class TechnicalService extends Model
{
    use HasFactory, SoftDeletes, GlobalFunctions;

    public $table = 'technical_services';

    protected $fillable = [
        'id', 'user_id', 'engine_service', 'mechanical_service', 
        'electrical_service', 'air_conditioning_service', 'computer_diagnostics_service', 
        'panel_beating_service', 'body_work_service', 'shock_absorber_service', 'ballon_shocks_service', 
        'wheel_balancing_and_alignment_service', 'car_wash_service', 'towing_service', 
        'created_at', 'updated_at', 'deleted_at'
    ];                                                    

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public static $columnsToExclude = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
    
    // protected $fillable;
    // public function __construct() {
    //     // $this->fillable = array_diff($this->allTableColumns($this->table), ['id', 'created_at', 'updated_at', 'deleted_at']);
    // }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
