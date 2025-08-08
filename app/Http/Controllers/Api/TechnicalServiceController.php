<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\TechnicalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TechnicalServiceController extends Controller
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
            Log::info('TechnicalServiceController store called');

            $columnsToExclude = TechnicalService::$columnsToExclude;
            $techServ = $this->getTableColumnsWithSort('technical_services', $columnsToExclude);

            Log::info('TechnicalServiceController result:', ['tech_services_count' => count($techServ), 'tech_services' => $techServ]);

            return response()->json([
                'success' => true,
                'data' => $techServ
            ]);

        } catch (\Exception $e) {
            Log::error('TechnicalServiceController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch technical services',
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
