<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessUser;
use App\Models\Address;
use App\Models\BusinessCategory;
use App\Models\User;
use App\Models\BusBrand;
use App\Models\CarBrand;
use App\Models\TruckBrand;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
use App\Models\Product;
use App\Models\SparePart;
use App\Models\TechnicalService;

class BusinessUserController extends Controller
{
    use GlobalFunctions;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = new User();
        $artisan = new Artisan();
        $product = new Product();
        $techService = new TechnicalService();
        $sparePart = new SparePart();
        $carBrnad = new CarBrand();
        $busBrand = new BusBrand();
        $truckBrand = new TruckBrand();
        $VehicleCategory = new VehicleCategory();
        // $artisanColumns = $this->getTableColumnsWithSort(Artisan::$table, Artisan::$columnsToExclude);
        $artisanColumns = $this->getTableColumnsWithSort($artisan->table, Artisan::$columnsToExclude);
        $productColumns = $this->getTableColumnsWithSort($product->table, Product::$columnsToExclude);
        $technicalServiceColumns = $this->getTableColumnsWithSort($techService->table, TechnicalService::$columnsToExclude);
        $sparePartColumns = $this->getTableColumnsWithSort($sparePart->table, SparePart::$columnsToExclude);
        $carColumns = $this->getTableColumnsWithSort($carBrnad->table, CarBrand::$columnsToExclude);
        $busColumns = $this->getTableColumnsWithSort($busBrand->table, BusBrand::$columnsToExclude);
        $truckColumns = $this->getTableColumnsWithSort($truckBrand->table, TruckBrand::$columnsToExclude);
        $VehicleCategoryColumns = $this->getTableColumnsWithSort($VehicleCategory->table, VehicleCategory::$columnsToExclude);

        return view('businessUser.profile', 
        [
            'artisanColumns' => $artisanColumns, 
            'productColumns' => $productColumns, 
            'technicalServiceColumns' => $technicalServiceColumns,
            'sparePartColumns' => $sparePartColumns,
            'carColumns' => $carColumns,
            'busColumns' => $busColumns,
            'truckColumns' => $truckColumns,
            'vehicleCategoryColumns' => $VehicleCategoryColumns
        ]);
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
        return view(
            'businessUser.edit', 
            [
                'businessUser' => User::findOrFail($id), 
                'address' => Address::where('user_id', '=', $id)->first(),
                'businessCategory' => BusinessCategory::where('user_id', '=', $id)->first(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBusinessUser $request, $id)
    {
        // Check if the $id exists
        $businessUser = User::findOrFail($id);
        // Get the values of the validated data
        $validated = $request->validated();
        $businessUser->fill($validated);
        $businessUser->save();

        // Check for address in table and update the record
        $address = Address::where('user_id', '=', $id)->first();
        $address->fill($validated);
        // For data that is not validated
        $address->address_line_2 = $request->input('address_line_2');
        $address->address_line_3 = $request->input('address_line_3');
        $address->save();

        // Check for business category in table and update the record
        $businessCategory = BusinessCategory::where('user_id', '=', $id)->first();
        $businessCategory->fill($validated);
        $businessCategory->save();

        // Save a message to the session
        $request->session()->flash('status', 'The user details was updated!');

        return redirect()->route('artisans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $businessUser = User::findOrFail($id);
        $businessUser->delete();

        session()->flash('status', 'User was deleted!');

        return redirect()->route('artisans.index');
    }
}
