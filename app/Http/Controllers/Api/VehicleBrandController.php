<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $selectedVehType = $request->selectedVehType;
            $pageName = $request->pageName;
            
            // Validate required fields
            if (empty($selectedVehType) || empty($pageName)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing required fields: selectedVehType and pageName are required'
                ], 400);
            }
            
            if (isset($request->selectedVehService)) {
                $vehServiceOrSpare = $request->selectedVehService;
            } elseif (isset($request->selectedVehSparePart)) {
                $vehServiceOrSpare = $request->selectedVehSparePart;
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing required field: either selectedVehService or selectedVehSparePart is required'
                ], 400);
            }

            Log::info('VehicleBrandController store called with:', [
                'pageName' => $pageName,
                'vehServiceOrSpare' => $vehServiceOrSpare,
                'selectedVehType' => $selectedVehType
            ]);

            $vehBrands = $this->getVehBrands($pageName, $vehServiceOrSpare, $selectedVehType);

            Log::info('VehicleBrandController result:', ['brands_count' => count($vehBrands), 'brands' => $vehBrands]);

            return response()->json([
                'success' => true,
                'data' => $vehBrands
            ]);

        } catch (\Exception $e) {
            Log::error('VehicleBrandController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch vehicle brands',
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
