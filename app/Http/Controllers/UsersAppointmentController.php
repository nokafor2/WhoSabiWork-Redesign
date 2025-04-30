<?php

namespace App\Http\Controllers;

use App\Models\UsersAppointment;
use App\Http\Requests\StoreUsersAppointmentRequest;
use App\Http\Requests\UpdateUsersAppointmentRequest;
use Illuminate\Http\Request;

class UsersAppointmentController extends Controller
{
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
        // dd($request->all());
        $userId = $request->user_id;
        $schedulerId = $request->scheduler_id;
        $appointmentDate = $request->appointment_date;
        $hours = implode(",", $request->hours);
        $appoitmentMessage = $request->appointment_message;
        $userDecision = $request->user_decision;

        // dd($userId, $schedulerId, $appointmentDate, $hours, $appoitmentMessage, $userDecision);
        $result = UsersAppointment::create([
            'user_id' => $userId, 
            'scheduler_id' => $schedulerId,
            'appointment_date' => $appointmentDate, 
            'hours' => $hours, 
            'appointment_message' => $appoitmentMessage,
            'user_decision' => $userDecision
        ]);

        return redirect()->back()->with('success', $result->id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsersAppointment $usersAppointment)
    {
        //
    }
}
