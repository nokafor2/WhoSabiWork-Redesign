<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\GlobalFunctions;

class Artisan extends Model
{
    use HasFactory, GlobalFunctions;

    protected $fillable = [
        'id', 'user_id', 'caterer', 'confectioner', 'event_planner', 'make_up_artist', 
        'tailor', 'jeweller', 'artist', 'tiler', 'holticulturist', 'driver', 'cleaner', 
        'mover', 'transporter', 'upholsterer', 'shoemaker', 'watchmaker', 'bag_maker', 
        'milliner', 'tanner', 'fabric_maker', 'brewer', 'detergent_maker', 'locksmith', 
        'welder', 'electronics_technician', 'bookbinder', 'carbinet_maker', 'gasman', 
        'glassblower', 'goldsmith', 'blacksmith', 'mason', 'plumber', 'electrician', 
        'painter', 'air_condition_technician', 'POP_maker', 'carpenter', 'security_guard', 
        'glazier', 'drycleaner', 'hair_stylist', 'wig_maker', 'photographer', 'computer_technician', 
        'phone_technician', 'solar_energy_technician', 'CCTV_technician', 'video_editor', 
        'barber', 'interior_designer', 'generator_technician', 'DSTV_technician', 'workout_trainer', 
        'alumaco_fabrication', 'fabric_printing', 'delivery_agent', 'diary_producer', 
        'cold_room_services', 'real_estate_agent', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'artisans';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public static $columnsToExclude = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
    // protected $fillable;
    // public function __construct() {
    //     // $this->fillable = array_diff($this->allTableColumns($this->table), ['id', 'created_at', 'updated_at', 'deleted_at']);
    // }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function stringArtisan() {
        $artisan = Artisan::find(1);
        $newArtisan = $artisan->toArray();
        $artisanKey = array_keys($newArtisan);

        return implode("', '", $artisanKey);
    }
}
