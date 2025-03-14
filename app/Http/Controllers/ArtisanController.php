<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisan;
use App\Http\Traits\GlobalFunctions;
use App\Models\Address;
use App\Models\Artisan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ArtisanController extends Controller
{
    use GlobalFunctions;
    
    public function __construct() {
        // This controller functions are protected by the auth middleware
        // Authentication by must be needed to access them
        // $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $artisans = $this->getUserCategoryDetails('artisan');

        $artisanObj = new Artisan();
        $artisanTypes = $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);

        return inertia(
            'Artisan/Index', 
            [ 
                'artisans' => $artisans,
                'artisanTypes' => $artisanTypes
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $businessCategory = $request->businessCategory;
        $categoryType = $request->categoryType;
        $state = $request->state;
        $town = $request->town;

        if ($categoryType === 'mechanic') {
            $vehService = $request->selectedVehService;
            $vehType = $request->selectedVehType;
            $vehBrand = $request->selectedVehBrand;
            $pageName = $request->pageName;

            $artisans = $this->getTechOrSpareUserDetails($pageName, $vehService, $vehType, $vehBrand, $state, $town);
        } else {
            $artisans = $this->getSpecifiedUserDetails($businessCategory, $categoryType, $state, $town);
        }

        return inertia(
            'Artisan/Index', 
            [ 
                'artisans' => $artisans,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArtisan $request, $id) {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        // 
    }
}
