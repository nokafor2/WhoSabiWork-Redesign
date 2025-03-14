<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TownController extends Controller
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
        $selectedOption = $request->selectedOption;
        $selectedState = $request->selectedState;
        $pageName = $request->pageName;
        $selectedVehType = $request->selectedVehType;
        $selectedVehBrand = $request->selectedVehBrand;

        if ($selectedOption === 'mechanic') {
            $vehServOrSpare = $request->selectedVehService;
            $towns = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $selectedVehType, $selectedVehBrand, $selectedState);
        } else if ($selectedOption === 'spare_parts') {
            $vehServOrSpare = $request->selectedVehSparePart;
            $towns = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $selectedVehType, $selectedVehBrand, $selectedState);
        } else {
            $towns = $this->getBussUserTown($selectedOption, $selectedState, $pageName);
        }

        // return Inertia::render(
        //     'Artisan/Index', ['towns' => $towns,]
        // );

        // used for manual data request without inertia
        return $towns;
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
