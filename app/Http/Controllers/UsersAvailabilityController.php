<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\User;
use App\Models\UsersAvailability;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class UsersAvailabilityController extends Controller
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
        $userId = $request->user_id;
        $date_available = $request->date_available;
        $selectedTime = $request->selectedTime;
        
        // check if record already exists before creating a new one
        $record = UsersAvailability::where([['user_id', '=', $userId], ['date_available', '=', $date_available]])->get();
        if (empty($record->toArray())) {
            // create new record
            $usersAvailability = new UsersAvailability();
            $usersAvailability->user_id = $userId;
            $usersAvailability->date_available = $date_available;
            $usersAvailabilityCols = $this->getTableColumnsWithSort($usersAvailability->table, UsersAvailability::$columnsToExclude)->toArray();
            foreach ($usersAvailabilityCols as $index => $usersAvailCol) {
                if (in_array($index, $selectedTime)) {
                    $usersAvailability->{$index} = true;
                } else {
                    $usersAvailability->{$index} = false;
                }
            }
            $result = UsersAvailability::create($usersAvailability->toArray());

            return redirect()->route('users.show', $userId)->with('success', $result->id);
        } else {
            // update the record
            $this->update($request, $record->first());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersAvailability $usersAvailability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsersAvailability $usersAvailability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsersAvailability $usersAvailability)
    {
        // dd($usersAvailability);
        $userId = $request->user_id;
        $selectedTime = $request->selectedTime;

        $usersAvailabilityCols = $this->getTableColumnsWithSort($usersAvailability->table, UsersAvailability::$columnsToExclude)->toArray();
        foreach ($usersAvailabilityCols as $index => $usersAvailCol) {
            if (in_array($index, $selectedTime)) {
                $usersAvailability->{$index} = true;
            } else {
                $usersAvailability->{$index} = false;
            }
        }
        $result = $usersAvailability->update();

        return redirect()->route('users.show', $userId)->with('success', $result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsersAvailability $usersAvailability)
    {
        //
    }
}
