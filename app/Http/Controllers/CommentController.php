<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use GlobalFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'body' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // check if user is authenticated
        if (!Auth::check()) {
            // return redirect()->back()->with('error', 'You must be logged in to comment');
            return redirect()->back()->with('error', 
                [
                    'authError' => 'unauthenticated',
                    'message' => 'You must be logged in to comment.',
                ]
            );
        }

        // store the comment in the database
        $comment = Comment::create([
            'user_id' => $request->user_id,
            'user_id_comment' => Auth::user()->id,
            'body' => $request->body,
        ]);

        // Get the updated entrepreneur data (the user being commented on)
        $entrepreneurData = $this->getUserDetails($request->user_id);

        return redirect()->back()
            ->with('success', [
                'message' => 'Comment was created!',
                'commentData' => $comment,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
