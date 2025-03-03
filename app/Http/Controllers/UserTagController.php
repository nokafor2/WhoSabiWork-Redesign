<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class UserTagController extends Controller
{
    // This will display all the users that have a tag associated
    // Pass in the tagId to know what resource to find
    public function index($tag) {
        $tag = Tag::findOrFail($tag);

        return view('customers.home', 
            // [
            //     'users' => $tag->users()
            //         ->withCount('comments')
            //         ->with('tags')
            //         ->get(),
            // ]
            // Modified to: with querscopes to reuse codes
            [
                'users' => $tag->users()
                    ->latestWithRelations()
                    ->get(),
            ]
        );
    }
}
