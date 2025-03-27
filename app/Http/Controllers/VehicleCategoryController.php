<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\User;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class VehicleCategoryController extends Controller
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
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $vehCatUser = VehicleCategory::where('user_id', '=', $user->id)->first();
        $vehCatObj = new VehicleCategory();
        $allVehCategories = $this->getTableColumnsWithSort($vehCatObj->table, VehicleCategory::$columnsToExclude);
        $selectedvehCat = $request->updateVal;
        foreach ($allVehCategories as $index => $VehicleCategory) {
            if (in_array($index, $selectedvehCat)) {
                $vehCatUser->{$index} = true;
            } else {
                $vehCatUser->{$index} = false;
            }
        }

        $result = $vehCatUser->save();

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
