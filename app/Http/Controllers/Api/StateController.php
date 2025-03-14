<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StateController extends Controller
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
    public function store(Request $request) {
        $selectedOption = $request->selectedOption;
        $pageName = $request->pageName;
        $vehType = $request->selectedVehType;
        $vehBrand = $request->selectedVehBrand;
        if ($selectedOption === 'mechanic') {
            $vehServOrSpare = $request->selectedVehService;
            $state = '';
            $states = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $vehType, $vehBrand, $state);
        } else if ($selectedOption === 'spare_parts') {
            $vehServOrSpare = $request->selectedVehSparePart;
            $state = '';
            $states = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $vehType, $vehBrand, $state);
        } else {
            $states = $this->getBussUserState($selectedOption, $pageName);
        }
        
        // return Inertia::render(
        //     'Artisan/Index', ['states' => $states,]
        // );

        // used for manual data request without inertia
        return $states;
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
