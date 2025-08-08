<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $selectedOption = $request->selectedOption;
            $pageName = $request->pageName;
            $vehType = $request->selectedVehType;
            $vehBrand = $request->selectedVehBrand;
            
            // Debug log the input
            Log::info('StateController input:', [
                'selectedOption' => $selectedOption,
                'pageName' => $pageName,
                'vehType' => $vehType,
                'vehBrand' => $vehBrand
            ]);
            
            // Validate required fields
            if (empty($selectedOption) || empty($pageName)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing required fields: selectedOption and pageName are required'
                ], 400);
            }
            
            if ($selectedOption === 'mechanic') {
                $vehServOrSpare = $request->selectedVehService;
                if (empty($vehServOrSpare)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'selectedVehService is required when selectedOption is mechanic'
                    ], 400);
                }
                $state = '';
                $states = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $vehType, $vehBrand, $state);
                Log::info('Mechanic states:', ['states' => $states]);
            } else if ($selectedOption === 'spare_parts') {
                $vehServOrSpare = $request->selectedVehSparePart;
                if (empty($vehServOrSpare)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'selectedVehSparePart is required when selectedOption is spare_parts'
                    ], 400);
                }
                $state = '';
                $states = $this->getStateTownTechOrSpare($pageName, $vehServOrSpare, $vehType, $vehBrand, $state);
                Log::info('Spare parts states:', ['states' => $states]);
            } else {
                $states = $this->getBussUserState($selectedOption, $pageName);
                Log::info('Regular states:', ['states' => $states]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $states
            ]);
        } catch (\Exception $e) {
            Log::error('StateController store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch states',
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
