<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\SparePart;
use App\Models\User;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    use GlobalFunctions;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $user = User::find($id);
        $sparePartObj = new SparePart();
        $spareParts = $this->getTableColumnsWithSort($sparePartObj->table, SparePart::$columnsToExclude);
        $selectedSpareParts = $request->updateVal;
        foreach ($spareParts as $index => $sparePart) {
            if (in_array($index, $selectedSpareParts)) {
                $sparePartObj->{$index} = true;
            } else {
                $sparePartObj->{$index} = false;
            }
        }

        $result = SparePart::create(['user_id' => $user->id, ...$sparePartObj->toArray()]);

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
        $user = User::find($id);
        $sparePartSeller = SparePart::where('user_id', '=', $user->id)->first();
        $sparePartObj = new SparePart();
        $spareParts = $this->getTableColumnsWithSort($sparePartObj->table, SparePart::$columnsToExclude);
        $selectedSpareParts = $request->updateVal;
        if ($sparePartSeller !== null) {
            // Update the record
            foreach ($spareParts as $index => $sparePart) {
                if (in_array($index, $selectedSpareParts)) {
                    $sparePartSeller->{$index} = true;
                } else {
                    $sparePartSeller->{$index} = false;
                }
            }
    
            $result = $sparePartSeller->save();
    
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
    public function destroy($id)
    {
        //
    }
}
