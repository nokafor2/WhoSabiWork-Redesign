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
        $truckBrandUser = TruckBrand::where('user_id', '=', $user->id)->first();
        $truckBrandObj = new TruckBrand();
        $allTruckBrands = $this->getTableColumnsWithSort($truckBrandObj->table, TruckBrand::$columnsToExclude);
        $selectedTruckBrands = $request->updateVal;
        foreach ($allTruckBrands as $index => $truckBrand) {
            if (in_array($index, $selectedTruckBrands)) {
                $truckBrandUser->{$index} = true;
            } else {
                $truckBrandUser->{$index} = false;
            }
        }

        $result = $truckBrandUser->save();

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
