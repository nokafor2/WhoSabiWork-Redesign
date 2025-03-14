<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechnicalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $selectedOption = $request->selectedOption;
        $selectedVehService = $request->selectedVehService;
        $pageName = $request->pageName;

        $data = [
            "selectedOption" => $selectedOption,
            "selectedVehService" => $selectedVehService,
            "pageName"=> $pageName
        ];

        $jsonData = json_encode($data);

        // return inertia(
        //     'Artisan/Index', 
        //     [ "data" => $data, ]
        // );

        return $jsonData;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
