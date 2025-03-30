<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\Product;
use App\Models\User;
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $user = User::find($id);
        $productObj = new Product();
        $marketerProducts = $this->getTableColumnsWithSort($productObj->table, Product::$columnsToExclude);
        $selectedProducts = $request->updateVal;
        foreach ($marketerProducts as $index => $marketerProduct) {
            if (in_array($index, $selectedProducts)) {
                $productObj->{$index} = true;
            } else {
                $productObj->{$index} = false;
            }
        }
    
        $result = Product::create(['user_id' => $user->id, ...$productObj->toArray()]);
    
        return redirect()->route('users.show', $id)->with('success', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $businessCategory = $request->businessCategory;
        $categoryType = $request->categoryType;
        $state = $request->state;
        $town = $request->town;
        
        if ($categoryType === 'spare_parts') {
            $vehSparePart = $request->selectedVehSparePart;
            $vehType = $request->selectedVehType;
            $vehBrand = $request->selectedVehBrand;
            $pageName = $request->pageName;

            $mobileMarketers = $this->getTechOrSpareUserDetails($pageName, $vehSparePart, $vehType, $vehBrand, $state, $town);
        } else {
            $mobileMarketers = $this->getSpecifiedUserDetails($businessCategory, $categoryType, $state, $town);
        }
        
        return inertia(
            'MobileMarket/Index', 
            ['mobileMarketers' => $mobileMarketers,]
        );
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
        $user = User::find($id);
        $mobileMarketer = Product::where('user_id', '=', $user->id)->first();
        $productObj = new Product();
        if ($mobileMarketer !== null) {
            // update the record
            $marketerProducts = $this->getTableColumnsWithSort($productObj->table, Product::$columnsToExclude);
            $selectedProducts = $request->updateVal;
            foreach ($marketerProducts as $index => $marketerProduct) {
                if (in_array($index, $selectedProducts)) {
                    $mobileMarketer->{$index} = true;
                } else {
                    $mobileMarketer->{$index} = false;
                }
            }
    
            $result = $mobileMarketer->save();
    
            return redirect()->route('users.show', $id)->with('success', $result);
        } else {
            // Create new record
            $this->create($request, $id);
        }
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
