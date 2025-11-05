<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhotographDislike;
use App\Models\PhotographLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PhotographLikeController extends Controller
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
        // Validate the input
        $validator = Validator::make($request->all(), [
            'photograph_id' => 'required|exists:photographs,id',
            'photograph_user_id' => 'required|exists:users,id',
            'like' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->with('error', 
                [
                    'authError' => 'unauthenticated',
                    'message' => 'You must be logged in to like a photograph.',
                ]
            );
        }

        // Check if the user has already liked the photograph
        $existingLike = PhotographLike::where('photograph_id', $request->photograph_id)
            ->where('user_id', Auth::user()->id)
            ->first();
        // dd($existingLike->toArray());
        
        // Check if the user already disliked the photograph
        $existingDislike = PhotographDislike::where('photograph_id', $request->photograph_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        // If user is toggling like off (unlike)
        if (!$request->like) {
            // User wants to unlike
            if ($existingLike) {
                $existingLike->update(['like' => 0]);
            }
            
            return redirect()->back()->with('success', [
                'like' => false,
                'dislike' => $existingDislike ? ($existingDislike->dislike == 1) : false,
                'dislikeUpdated' => false,
                'message' => 'You unliked this photograph',
            ]);
        }
        
        // If user is liking the photograph
        if ($request->like) {
            // Create or update the like
            if ($existingLike) {
                $existingLike->update(['like' => 1]);
            } else {
                PhotographLike::create([
                    'photograph_id' => $request->photograph_id,
                    'photograph_user_id' => $request->photograph_user_id,
                    'user_id' => Auth::user()->id,
                    'like' => 1,
                ]);
            }
            
            // If user previously disliked, remove the dislike
            if ($existingDislike && $existingDislike->dislike == 1) {
                $existingDislike->update(['dislike' => 0]);
                $dislikeUpdated = true;
            } else {
                $dislikeUpdated = false;
            }
            
            return redirect()->back()->with('success', [
                'like' => true,
                'dislike' => false,
                'dislikeUpdated' => $dislikeUpdated,
                'message' => 'You liked this photograph',
            ]);
        }
        
        return redirect()->back()->with('success', [
            'like' => false,
            'dislike' => $existingDislike ? $existingDislike->dislike : null,
            'dislikeUpdated' => false,
            'message' => 'No action taken',
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
