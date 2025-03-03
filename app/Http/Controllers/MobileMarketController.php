<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\Product;
use Illuminate\Http\Request;

class MobileMarketController extends Controller
{
    use GlobalFunctions;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $mobileMarketers = $this->getUserCategoryDetails('seller');

        $productObj = new Product();
        $sellers = $this->getTableColumnsWithSort($productObj->table, Product::$columnsToExclude);

        return inertia(
            'MobileMarket/Index', 
            [ 
                'mobileMarketers' => $mobileMarketers,
                'products' => $sellers
            ]
        );

        // $businessCategory = $request->businessCategory;
        // $categoryType = $request->categoryType;
        // $state = $request->state;
        // $town = $request->town;
        
        // $artisans = $this->getSpecifiedUserDetails($businessCategory, $categoryType, $state, $town);
        // // return ['artisans' => $artisans];

        // return inertia(
        //     'Artisan/Index', 
        //     [ 
        //         'artisans' => $artisans,
        //     ]
        // );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
