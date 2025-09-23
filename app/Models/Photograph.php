<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Photograph extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'filename', 'path', 'size', 'caption', 'photo_type', 'visible'];
    protected $appends = ['src'];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function url() {
        return Storage::url($this->path);
    }

    public function getSrcAttribute() {
        return asset("storage/{$this->path}");
    }
}
