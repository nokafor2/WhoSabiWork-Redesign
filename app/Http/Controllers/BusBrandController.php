<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\BusBrand;
use App\Models\User;
use Illuminate\Http\Request;

class BusBrandController extends Controller
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
        //
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
        $user = User::find($id);
        $busBrandUser = BusBrand::where('user_id', '=', $user->id)->first();
        $busBrandObj = new BusBrand();
        $allBusBrands = $this->getTableColumnsWithSort($busBrandObj->table, BusBrand::$columnsToExclude);
        $selectedBusBrands = $request->updateVal;
        foreach ($allBusBrands as $index => $busBrand) {
            if (in_array($index, $selectedBusBrands)) {
                $busBrandUser->{$index} = true;
            } else {
                $busBrandUser->{$index} = false;
            }
        }

        $result = $busBrandUser->save();

        return redirect()->route('users.show', $id)->with('success', $result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
