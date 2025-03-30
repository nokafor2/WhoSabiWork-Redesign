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
    public function create(Request $request, string $id)
    {
        $user = User::find($id);
        $vehCatObj = new VehicleCategory();
        $allVehCategories = $this->getTableColumnsWithSort($vehCatObj->table, VehicleCategory::$columnsToExclude);
        $selectedvehCat = $request->updateVal;
        $businessCategory = $request->businessCategory;
        foreach ($allVehCategories as $index => $VehicleCategory) {
            if (in_array($index, $selectedvehCat)) {
                $vehCatObj->{$index} = true;
            } else {
                $vehCatObj->{$index} = false;
            }
        }

        $result = VehicleCategory::create(['user_id' => $user->id, 'business_category' => $businessCategory, ...$vehCatObj->toArray()]);

        return redirect()->route('users.show', $id)->with('success', $result);
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
        $businessCategory = $request->businessCategory;
        $vehCatUser = VehicleCategory::where([['user_id', '=', $user->id], ['business_category', '=', $businessCategory]])->first();
        $vehCatObj = new VehicleCategory();
        $allVehCategories = $this->getTableColumnsWithSort($vehCatObj->table, VehicleCategory::$columnsToExclude);
        $selectedvehCat = $request->updateVal;
        if ($vehCatUser !== null) {
            // update the current record
            foreach ($allVehCategories as $index => $VehicleCategory) {
                if (in_array($index, $selectedvehCat)) {
                    $vehCatUser->{$index} = true;
                } else {
                    $vehCatUser->{$index} = false;
                }
            }

            $result = $vehCatUser->save();
    
            return redirect()->route('users.show', $id)->with('success', $result);
        } else {
            // Create a new record
            $this->create($request, $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
