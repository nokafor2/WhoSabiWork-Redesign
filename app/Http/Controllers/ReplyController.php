<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Reply;

class ReplyController extends Controller
{
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
        try {
            // Validate the reply data received from the request
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required|exists:comments,id', // comment id is the id of the comment that is being replied to
                'user_id_comment' => 'required|exists:users,id', // user id is the id of the user who is commenting on the photograph
                'body' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You must be logged in to reply');
            }

            $reply = Reply::create([
                'comment_id' => $request->comment_id,
                'user_id_comment' => $request->user_id_comment,
                'user_id_reply' => Auth::user()->id,
                'body' => $request->body
            ]);
            
            // Load the reply with relationships to return complete data
            $reply->load('replyUser', 'commentUser');
            
            // Get replier avatar
            $replierAvatar = $reply->replyUser->photographs()
                ->where('photo_type', 'avatar')
                ->latest()
                ->first();
            
            // Prepare reply data with all necessary information (properly serialized)
            $replyData = [
                'id' => $reply->id,
                'replierFullName' => $reply->replyUser->userFullName(),
                'replierAvatar' => $replierAvatar ? asset('storage/' . $replierAvatar->path) : null,
                'replyDate' => $reply->created_at->diffForHumans(),
                'reply' => $reply->body,
                'comment_id' => $reply->comment_id,
            ];
            
            return redirect()->back()->with('success', $replyData);
        } catch (\Exception $e) {
            Log::error('ReplyController::store error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Failed to submit reply');
        }
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
