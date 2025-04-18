<?php

namespace App\Models;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersFeedback extends Model
{
    /** @use HasFactory<\Database\Factories\UsersFeedbackFactory> */
    use HasFactory, GlobalFunctions, SoftDeletes;

    public $table = 'users_feedback';
    
    protected $fillable = ['first_name', 'last_name', 'phone_number', 'email', 'message_subject', 'message_content', 'resolved'];
}
