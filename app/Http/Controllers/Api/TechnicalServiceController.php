<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\TechnicalService;
use Illuminate\Http\Request;

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
        $columnsToExclude = TechnicalService::$columnsToExclude;
        $techServ = $this->getTableColumnsWithSort('technical_services', $columnsToExclude);

        return $techServ;
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
