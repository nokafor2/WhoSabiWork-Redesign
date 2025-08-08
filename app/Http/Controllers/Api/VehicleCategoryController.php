<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $pageName = $request->pageName;
            
            // Validate required fields
            if (empty($pageName)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Missing required field: pageName is required'
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

            Log::info('VehicleCategoryController store called with:', [
                'pageName' => $pageName,
                'vehServiceOrSpare' => $vehServiceOrSpare
            ]);

            $availVehCategory = $this->getVehCategories($pageName, $vehServiceOrSpare);

            Log::info('VehicleCategoryController result:', ['categories_count' => count($availVehCategory), 'categories' => $availVehCategory]);

            return response()->json([
                'success' => true,
                'data' => $availVehCategory
            ]);

        } catch (\Exception $e) {
            Log::error('VehicleCategoryController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch vehicle categories',
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
