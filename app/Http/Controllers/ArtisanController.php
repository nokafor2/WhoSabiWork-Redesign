<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
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
        $artisans = [];

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
    public function create(Request $request, $id) {
        $user = User::find($id);
        $artisanObj = new Artisan();
        $artisanTypes = $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);
        $selectedArtisans = $request->updateVal;
        foreach ($artisanTypes as $index => $artisan) {
            if (in_array($index, $selectedArtisans)) {
                $artisanObj->{$index} = true;
            } else {
                $artisanObj->{$index} = false;
            }
        }
        // dd($artisanObj->toArray());
        $result = Artisan::create(['user_id' => $user->id, ...$artisanObj->toArray()]);

        return redirect()->route('users.show', $id)->with('success', $result);
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
        $page = $request->get('page', 1);
        $perPage = 15;

        // Build cache key based on search parameters
        $cacheKey = "artisans_{$businessCategory}_{$categoryType}_{$state}_{$town}_page_{$page}";
        
        if ($categoryType === 'mechanic') {
            $vehService = $request->selectedVehService;
            $vehType = $request->selectedVehType;
            $vehBrand = $request->selectedVehBrand;
            $pageName = $request->pageName;
            
            // Update cache key for mechanic search
            $cacheKey = "artisans_mechanic_{$vehService}_{$vehType}_{$vehBrand}_{$state}_{$town}_page_{$page}";

            // Cache for 10 minutes (600 seconds)
            $artisans = Cache::remember($cacheKey, 600, function () use ($pageName, $vehService, $vehType, $vehBrand, $state, $town, $perPage) {
                return $this->getTechOrSpareUserDetailsPaginated($pageName, $vehService, $vehType, $vehBrand, $state, $town, $perPage);
            });
        } else {
            // Cache for 10 minutes (600 seconds)
            $artisans = Cache::remember($cacheKey, 600, function () use ($businessCategory, $categoryType, $state, $town, $perPage) {
                return $this->getSpecifiedUserDetailsPaginated($businessCategory, $categoryType, $state, $town, $perPage);
            });
        }

        // Get artisan types for the dropdown (cache for 30 minutes)
        $artisanObj = new Artisan();
        $artisanTypes = Cache::remember('artisan_types', 1800, function () use ($artisanObj) {
            return $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);
        });

        return inertia('Artisan/Index', [ 
            'artisans' => $artisans,
            'artisanTypes' => $artisanTypes
        ]);
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
    public function update(Request $request, $id) {
        $user = User::find($id);
        $artisanUser = Artisan::where('user_id', '=', $user->id)->first();
        $artisanObj = new Artisan();
        if ($artisanUser !== null) {
            // Update record
            $artisanTypes = $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);
            $selectedArtisans = $request->updateVal;
            foreach ($artisanTypes as $index => $artisan) {
                if (in_array($index, $selectedArtisans)) {
                    $artisanUser->{$index} = true;
                } else {
                    $artisanUser->{$index} = false;
                }
            }

            $result = $artisanUser->save();

            return redirect()->route('users.show', $id)->with('success', $result);
        } else {
            // Create a new 
            $this->create($request, $id);
        }
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
