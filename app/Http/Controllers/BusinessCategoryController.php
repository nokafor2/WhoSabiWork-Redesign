<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\BusinessCategory;
use App\Models\User;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
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
        //
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
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $businessCategory = BusinessCategory::where('user_id', '=', $user->id)->first();
        if (is_array($request->updateVal)) {
            $allCategories = ['artisan', 'seller', 'technician', 'spare_part_seller'];
            $categories = $request->updateVal;
            foreach ($allCategories as $category) {
                if (in_array($category, $categories)) {
                    $businessCategory->{$category} = true;
                } else {
                    $businessCategory->{$category} = false;
                }
            }
            $result = $businessCategory->save();
        } else {
            $updateVal = $request->updateVal;
            $updateField = $request->updateField;
            $businessCategory->{$updateField} = $updateVal;
            $result = $businessCategory->save();
        }
        

        return redirect()->route('users.show', $id)->with('success', $result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
