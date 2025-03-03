<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;
use Illuminate\Http\Request;

class UserCommentController extends Controller
{    
    public function __construct()
    {
        // Require authentication before users can send anything for this action
        $this->middleware('auth')->only(['store']);
    }
    
    public function store(User $user, StoreComment $request) {
        // dd($user);
        // $user->id: gives the details of the user being commented on through the late route model binding
        // The user-id passed in from the route() method will be used to get the $user details from the database
        // Hence no need to do a findOfFail($id)
        // $request->user()->id: gives the details of the currently logged in user
        // $validated = $request->validated();
        $user->comments()->create([
            'user_id' => $request->user()->id,
            'business_id' => $user->id,
            'body' => $request->input('body')
        ]);

        $request->session()->flash('status', 'Comment was created!');

        return redirect()->back();
    }
}
