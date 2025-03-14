<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleBrandController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (isset($request->selectedVehService)) {
            $vehServiceOrSpare = $request->selectedVehService;
        } elseif (isset($request->selectedVehSparePart)) {
            $vehServiceOrSpare = $request->selectedVehSparePart;
        }
        $selectedVehType = $request->selectedVehType;
        $pageName = $request->pageName;

        $vehBrands = $this->getVehBrands($pageName, $vehServiceOrSpare, $selectedVehType);

        return $vehBrands;

        // return Inertia('Artisan/Index', $vehBrands);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
