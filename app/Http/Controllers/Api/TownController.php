<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $selectedOption = $request->selectedOption;
            $selectedState = $request->selectedState;
            $pageName = $request->pageName;
            $selectedVehType = $request->selectedVehType;
            $selectedVehBrand = $request->selectedVehBrand;
            
            // Validate required fields
            if (empty($selectedOption) || empty($selectedState) || empty($pageName)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing required fields: selectedOption, selectedState, and pageName are required'
                ], 400);
            }
            
            Log::info('TownController store called with:', [
                'selectedOption' => $selectedOption,
                'selectedState' => $selectedState,
                'pageName' => $pageName,
                'selectedVehType' => $selectedVehType,
                'selectedVehBrand' => $selectedVehBrand
            ]);

            if ($selectedOption === 'mechanic') {
                $vehServOrSpare = $request->selectedVehService;
                if (empty($vehServOrSpare)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'selectedVehService is required for mechanic option'
                    ], 400);
                }
                $towns = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $selectedVehType, $selectedVehBrand, $selectedState);
            } else if ($selectedOption === 'spare_parts') {
                $vehServOrSpare = $request->selectedVehSparePart;
                if (empty($vehServOrSpare)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'selectedVehSparePart is required for spare_parts option'
                    ], 400);
                }
                $towns = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $selectedVehType, $selectedVehBrand, $selectedState);
            } else {
                $towns = $this->getBussUserTown($selectedOption, $selectedState, $pageName);
            }

            Log::info('TownController result:', ['towns_count' => count($towns), 'towns' => $towns]);

            return response()->json([
                'success' => true,
                'data' => $towns
            ]);

        } catch (\Exception $e) {
            Log::error('TownController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch towns',
                'message' => $e->getMessage()
            ], 500);
        }
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
