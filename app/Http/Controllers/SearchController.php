<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
use App\Models\Product;
use Illuminate\Http\Request;
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
        
        $searchedResult = $this->searchData($searchVal, $pageName);
        // dd($searchedResult);

        // return redirect()->back()->with('success', $searchedResult);
        return response()->json([
            'success' => true,
            'data' => $searchedResult,
            'pageName' => $pageName,
            'count' => count($searchedResult)
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
        // dd($request->selectedOption);

        $selectedOption = $request->selectedOption;

        $states = $this->getBussUserState($selectedOption);

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
