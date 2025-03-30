<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\CarBrand;
use App\Models\User;
use Illuminate\Http\Request;

class CarBrandController extends Controller
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
        $carBrandObj = new CarBrand();
        $allCarBrands = $this->getTableColumnsWithSort($carBrandObj->table, CarBrand::$columnsToExclude);
        $selectedCarBrands = $request->updateVal;
        $businessCategory = $request->businessCategory;
        foreach ($allCarBrands as $index => $carBrand) {
            if (in_array($index, $selectedCarBrands)) {
                $carBrandObj->{$index} = true;
            } else {
                $carBrandObj->{$index} = false;
            }
        }

        $result = CarBrand::create(['user_id' => $user->id, 'business_category' => $businessCategory, ...$carBrandObj->toArray()]);

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
        $carBrandUser = CarBrand::where([['user_id', '=', $user->id], ['business_category', '=', $businessCategory]])->first();
        $carBrandObj = new CarBrand();
        $allCarBrands = $this->getTableColumnsWithSort($carBrandObj->table, CarBrand::$columnsToExclude);
        $selectedCarBrands = $request->updateVal;
        if ($carBrandUser !== null) {
            // Update the record
            foreach ($allCarBrands as $index => $carBrand) {
                if (in_array($index, $selectedCarBrands)) {
                    $carBrandUser->{$index} = true;
                } else {
                    $carBrandUser->{$index} = false;
                }
            }
    
            $result = $carBrandUser->save();
    
            return redirect()->route('users.show', $id)->with('success', $result);
        } else {
            // Create new record
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
