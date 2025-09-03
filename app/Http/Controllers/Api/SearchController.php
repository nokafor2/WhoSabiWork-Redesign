<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $searchVal = $request->searchVal;
        $pageName = $request->pageName;
        
        $searchedResult = $this->searchData($searchVal, $pageName);
        // dd($searchedResult);
        $artisans = $this->getUserCategoryDetails('artisan');

        $artisanObj = new Artisan();
        $artisanTypes = $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);

        // return Inertia('Artisan/Index', ['artisans' => $artisans, 'artisanTypes' => $artisanTypes])->with("result", $searchedResult);
        // return redirect()->back()->with('result', $searchedResult);

        // return Redirect::route('artisans.index')->with("success", $searchedResult);
        // return redirect()->route('artisans.index')->with("success", $searchedResult);

        // return $searchedResult;
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
