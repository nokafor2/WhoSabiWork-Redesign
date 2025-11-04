<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhotographDislike;
use App\Models\PhotographLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PhotographDislikeController extends Controller
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
            'dislike' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'You must be logged in to dislike a photograph');
        }

        // Check if the user has already disliked the photograph
        $existingDislike = PhotographDislike::where('photograph_id', $request->photograph_id)
            ->where('user_id', Auth::user()->id)
            ->first();
        
        // Check if the user already liked the photograph
        $existingLike = PhotographLike::where('photograph_id', $request->photograph_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        // If user is toggling dislike off (undislike)
        if ($existingDislike && !$request->dislike) {
            $existingDislike->update(['dislike' => 0]);
            
            return redirect()->back()->with('success', [
                'like' => $existingLike ? ($existingLike->like == 1) : false,
                'dislike' => false,
                'message' => 'You removed your dislike from this photograph',
            ]);
        }
        
        // If user is disliking the photograph
        if ($request->dislike) {
            // Create or update the dislike
            if ($existingDislike) {
                $existingDislike->update(['dislike' => 1]);
            } else {
                PhotographDislike::create([
                    'photograph_id' => $request->photograph_id,
                    'photograph_user_id' => $request->photograph_user_id,
                    'user_id' => Auth::user()->id,
                    'dislike' => 1,
                ]);
            }
            
            // If user previously liked, remove the like
            if ($existingLike && $existingLike->like == 1) {
                $existingLike->update(['like' => 0]);
            }
            
            return redirect()->back()->with('success', [
                'like' => false,
                'dislike' => true,
                'message' => 'You disliked this photograph',
            ]);
        }
        
        return redirect()->back()->with('success', [
            'like' => $existingLike ? ($existingLike->like == 1) : false,
            'dislike' => false,
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
