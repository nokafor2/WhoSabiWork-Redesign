<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SparePartController extends Controller
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
            Log::info('SparePartController store called');

            $columnsToExclude = SparePart::$columnsToExclude;
            $spareParts = $this->getTableColumnsWithSort('spare_parts', $columnsToExclude);

            Log::info('SparePartController result:', ['spare_parts_count' => count($spareParts), 'spare_parts' => $spareParts]);

            return response()->json([
                'success' => true,
                'data' => $spareParts
            ]);

        } catch (\Exception $e) {
            Log::error('SparePartController store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch spare parts',
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
