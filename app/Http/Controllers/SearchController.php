<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SearchController extends Controller
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
        $searchVal = $request->searchVal;
        $pageName = $request->pageName;
        $page = $request->get('page', 1);
        $perPage = 15;
        
        // Build cache key based on search parameters
        $cacheKey = "search_{$pageName}_{$searchVal}_page_{$page}";
        
        // Cache search results for 5 minutes (300 seconds)
        // Shorter cache time for searches since results may be more dynamic
        $searchedResult = Cache::remember($cacheKey, 300, function () use ($searchVal, $pageName, $perPage) {
            return $this->searchDataPaginated($searchVal, $pageName, $perPage);
        });

        // return redirect()->back()->with('success', $searchedResult);
        return response()->json([
            'success' => true,
            'data' => $searchedResult,
            'pageName' => $pageName,
            'count' => $searchedResult->total()
        ]);
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

    public function getState(Request $request)
    {
        $selectedOption = $request->selectedOption;
        $pageName = $request->pageName;

        $states = $this->getBussUserState($selectedOption, $pageName);

        // return inertia(
        //     'Artisan/Index', 
        //     [ 
        //         'states' => $states,
        //     ]
        // );

        return Inertia::render(
            'Artisan/Index', 
            [
                'states' => $states,
            ]
        );
        // return redirect()->route('users.create')->with('success', 'Account was successfully created!');
        // return redirect()->route('artisans.index')->with('states', $states);

        // session(['states' => $states]);
        // return redirect()->route('artisans.index');
    }
}
