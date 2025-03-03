<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\GlobalFunctions;

class Product extends Model
{
    use HasFactory, SoftDeletes, GlobalFunctions;

    public $table = 'products';

    protected $fillable = [
        'id', 'user_id', 'provisions', 'fabrics', 'electronics', 'electricals_and_accessories', 'computers_and_accessories', 
        'phones_and_accessories', 'kitchen_utensils', 'house_hold_equipment', 'jeweries', 'shoes', 
        'clothes_and_apparels', 'televisions', 'bags_and_boxes', 'tiles', 'building_materials', 'furniture', 
        'cars', 'motorcycles', 'tricycles', 'paints', 'books', 'decors', 'generators', 'watches', 'hairs_and_wigs', 
        'exercise_equipment', 'plumbimg_materials', 'fashion_accessories', 'pharmaceuticals', 'fragrances_and_ornamentals', 
        'fishery', 'skin_care_products', 'hair_products', 'baby_care_accessories', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // protected $fillable;
    // public function __construct() {
    //     // $this->fillable = array_diff($this->allTableColumns($this->table), ['id', 'created_at', 'updated_at', 'deleted_at']);
    // }

    // public function getFillable() {
    //     return $this->fillable;
    // }

    public static $columnsToExclude = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];

    public function getGuarded() {
        return $this->guarded;
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
