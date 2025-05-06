<?php

namespace App\Http\Controllers;

use App\Models\UsersAppointment;
use App\Http\Requests\StoreUsersAppointmentRequest;
use App\Http\Requests\UpdateUsersAppointmentRequest;
use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersAppointmentController extends Controller
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
        // Check if appointment exists
        $userId = $request->user_id;
        $schedulerId = $request->scheduler_id;
        $appointmentDate = $request->appointment_date;
        $availableAppointment = UsersAppointment::where([['user_id', '=', $userId], ['scheduler_id', '=', $schedulerId], ['appointment_date', '=', $appointmentDate]])->get();
        // dd($availableAppointment->first()->id);
        if (empty($availableAppointment->toArray())) {
            $hours = implode(",", $request->hours);
            $availableHours = $this->getAllScheduleTime();

            $validated = $request->validate([
                'user_id' => ['required', 'numeric'],
                'scheduler_id' => ['required', 'numeric'],
                'appointment_date' => ['required', 'date_format:Y-m-d'],
                'hours' => ['required', 'array'],
                'hours.*' => ['in:'.implode(",", $availableHours)],
                'appointment_message' => ['required', 'string', 'max:250'],
                'user_decision' => ['required', 'string', 'max:10'],
            ]);

            $result = UsersAppointment::create([
                ...$validated,
                'hours' => $hours,
            ]);

            return redirect()->back()->with('success', $result->id);
        } else {
            // Update the record
            $this->update($request, $availableAppointment->first());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersAppointment $usersAppointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsersAppointment $usersAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * UpdateUsersAppointmentRequest
     */
    public function update(Request $request, UsersAppointment $usersAppointment)
    {
        dd($usersAppointment->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsersAppointment $usersAppointment)
    {
        //
    }
}
