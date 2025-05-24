<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersAppointment extends Model
{
    /** @use HasFactory<\Database\Factories\UsersAppointmentFactory> */
    use HasFactory, GlobalFunctions, SoftDeletes;

    public $table = 'users_appointments';

    protected $fillable = ['user_id', 'scheduler_id', 'appointment_date', 'hours', 
    'appointment_message', 'user_decision', 'user_decline_message', 'scheduler_cancel_message', 
    'user_cancel_message'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function scheduler() {
        return $this->belongsTo('App\Models\User', 'scheduler_id');
    }
}
