<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\TruckBrand;
use App\Models\User;
use Illuminate\Http\Request;

class TruckBrandController extends Controller
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
        $truckBrandObj = new TruckBrand();
        $allTruckBrands = $this->getTableColumnsWithSort($truckBrandObj->table, TruckBrand::$columnsToExclude);
        $selectedTruckBrands = $request->updateVal;
        $businessCategory = $request->businessCategory;
        foreach ($allTruckBrands as $index => $truckBrand) {
            if (in_array($index, $selectedTruckBrands)) {
                $truckBrandObj->{$index} = true;
            } else {
                $truckBrandObj->{$index} = false;
            }
        }

        $result = TruckBrand::create(['user_id' => $user->id, 'business_category' => $businessCategory, ...$truckBrandObj->toArray()]);

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
        $truckBrandUser = TruckBrand::where([['user_id', '=', $user->id], ['business_category', '=', $businessCategory]])->first();
        $truckBrandObj = new TruckBrand();
        $allTruckBrands = $this->getTableColumnsWithSort($truckBrandObj->table, TruckBrand::$columnsToExclude);
        $selectedTruckBrands = $request->updateVal;
        if ($truckBrandUser !== null) {
            foreach ($allTruckBrands as $index => $truckBrand) {
                if (in_array($index, $selectedTruckBrands)) {
                    $truckBrandUser->{$index} = true;
                } else {
                    $truckBrandUser->{$index} = false;
                }
            }
    
            $result = $truckBrandUser->save();
    
            return redirect()->route('users.show', $id)->with('success', $result);
        } else {
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
