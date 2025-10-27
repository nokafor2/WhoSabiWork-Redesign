<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhotographReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhotographReplyController extends Controller
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
                'photograph_id' => 'required|exists:photographs,id',
                'comment_id' => 'required|exists:photographs_comments,id', // comment id is the id of the comment that is being replied to
                'user_id_comment' => 'required|exists:users,id', // user id is the id of the user who is commenting on the photograph
                'reply' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You must be logged in to reply');
            }

            $reply = PhotographReply::create([
                'photograph_id' => $request->photograph_id,
                'comment_id' => $request->comment_id,
                'user_id_comment' => $request->user_id_comment,
                'user_id_reply' => Auth::user()->id,
                'reply' => $request->reply
            ]);
            
            // Load the reply with relationships to return complete data
            $reply->load('replyUser', 'commentOwner');
            
            // Get replier avatar
            $replierAvatar = $reply->replyUser->photographs()
                ->where('photo_type', 'avatar')
                ->latest()
                ->first();
            
            // Prepare reply data with all necessary information (properly serialized)
            $replyData = [
                'id' => $reply->id,
                'photograph_id' => $reply->photograph_id,
                'comment_id' => $reply->comment_id,
                'user_id_comment' => $reply->user_id_comment,
                'user_id_reply' => $reply->user_id_reply,
                'reply' => $reply->reply,
                'created_at' => $reply->created_at->toISOString(),
                'updated_at' => $reply->updated_at->toISOString(),
                'replier_full_name' => $reply->replyUser->first_name . ' ' . $reply->replyUser->last_name,
                'replier_avatar' => $replierAvatar ? asset('storage/' . $replierAvatar->path) : null,
                'created_at_human' => $reply->created_at->diffForHumans(),
                'reply_user' => [
                    'id' => $reply->replyUser->id,
                    'first_name' => $reply->replyUser->first_name,
                    'last_name' => $reply->replyUser->last_name,
                    'username' => $reply->replyUser->username,
                    'email' => $reply->replyUser->email,
                ],
                'comment_owner' => $reply->commentOwner ? [
                    'id' => $reply->commentOwner->id,
                    'first_name' => $reply->commentOwner->first_name,
                    'last_name' => $reply->commentOwner->last_name,
                    'username' => $reply->commentOwner->username,
                    'email' => $reply->commentOwner->email,
                ] : null,
            ];
            
            return redirect()->back()->with('success', $replyData);
        } catch (\Exception $e) {
            Log::error('PhotographReplyController::store error', [
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
