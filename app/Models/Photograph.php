<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photograph extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'filename', 'path', 'size', 'caption', 'photo_type', 'visible'];

    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    public function url() {
        return Storage::url($this->path);
    }
}
