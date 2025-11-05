<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\PhotographComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PhotographCommentController extends Controller
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
        // Check for validation errors of input
        $validator = Validator::make($request->all(), [
            'photograph_id' => 'required|exists:photographs,id',
            'photograph_user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->with('error', 
                [
                    'authError' => 'unauthenticated',
                    'message' => 'You must be logged in to comment',
                ]
            );
        }

        $photographId = $request->photograph_id;
        // Store the comment in the database
        $comment = PhotographComment::create([
            'photograph_id' => $photographId,
            'photograph_user_id' => $request->photograph_user_id,
            'user_id_comment' => Auth::user()->id,
            'comment' => $request->comment,
        ]);

        // Load the comment with relationships for proper serialization
        $comment->load('commentUser');
        
        // Get commenter avatar
        $commenterAvatar = null;
        if ($comment->commentUser) {
            $avatar = $comment->commentUser->photographs()
                ->where('photo_type', 'avatar')
                ->latest()
                ->first();
            $commenterAvatar = $avatar ? asset('storage/' . $avatar->path) : null;
        }
        
        // Prepare the comment data to return
        $commentData = [
            'id' => $comment->id,
            'photograph_id' => $comment->photograph_id,
            'photograph_user_id' => $comment->photograph_user_id,
            'user_id_comment' => $comment->user_id_comment,
            'comment' => $comment->comment,
            'created_at' => $comment->created_at->toISOString(),
            'updated_at' => $comment->updated_at->toISOString(),
            'created_at_human' => $comment->created_at->diffForHumans(),
            'commenter_full_name' => $comment->commentUser ? 
                $comment->commentUser->first_name . ' ' . $comment->commentUser->last_name : 'Unknown',
            'commenter_avatar' => $commenterAvatar,
            'photograph_replies' => [], // New comment has no replies yet
        ];

        // Get the updated photo data including all comments and replies
        // $photoData = $this->getPhotoData($photographId);

        // Get the comment count for the photograph
        $commentCount = PhotographComment::where('photograph_id', $photographId)->count();

        return redirect()->back()->with('success', [
            'message' => 'Comment was created!',
            'commentData' => $commentData,
            'commentCount' => $commentCount,
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
