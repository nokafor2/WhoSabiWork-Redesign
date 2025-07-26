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
<<<<<<< HEAD
        // creates new appointment if it doesn't exists
=======
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
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
<<<<<<< HEAD
            // dd($availableAppointment->first());
            // dd($request->all());
=======
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
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
<<<<<<< HEAD
        if (($request->user_decision === 'accepted') || ($request->user_decision === 'declined')
             || ($request->user_decision === 'cancelled')
        ) {
=======
        if (($request->user_decision === 'accepted') || ($request->user_decision === 'declined')) {
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
            $appointmentId = $request->id;
            $userId = $request->user_id;
            $schedulerId = $request->scheduler_id;
            $usersAppointment = UsersAppointment::where('id', '=', $appointmentId)->first();
            $usersAppointment->user_decision = $request->user_decision;
            $validated = $request->validate([
                'user_decision' => ['required', 'string', 'max:10'],
            ]);
            // Check if there is a decline message
            if ($request->user_decision === 'declined') {
<<<<<<< HEAD
                // dd($request->all());
=======
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
                $validated = $request->validate([
                    'user_decline_message' => ['required', 'string', 'max:250'],
                    'user_decision' => ['required', 'string', 'max:10'],
                ]);
                // $usersAppointment->user_decline_message = $request->user_decline_message;
<<<<<<< HEAD
            } elseif ($request->user_decision === 'cancelled') {
                if (array_key_exists('user_cancel_message', $request->all())) {
                    $validated = $request->validate([
                        'user_cancel_message' => ['required', 'string', 'max:250'],
                        'user_decision' => ['required', 'string', 'max:10'],
                    ]);
                } elseif (array_key_exists('scheduler_cancel_message', $request->all())) {
                    $validated = $request->validate([
                        'scheduler_cancel_message' => ['required', 'string', 'max:250'],
                        'user_decision' => ['required', 'string', 'max:10'],
                    ]);
                }
=======
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
            }
            $result = $usersAppointment->update($validated);
            // Check delete status
            if ($result) {
                $appointmentDetails = $this->getAppointments('neutral', $userId, $schedulerId);
                return redirect()->route('users.show', $userId)->with('success', $appointmentDetails);
            } else {
                return redirect()->route('users.show', $userId)->with('success', $result);
            } 
        } else {
            $hours = implode(",", $request->hours);
            $availableHours = $this->getAllScheduleTime();

            $validated = $request->validate([
                'hours' => ['required', 'array'],
                'hours.*' => ['in:'.implode(",", $availableHours)],
                'appointment_message' => ['required', 'string', 'max:250'],
<<<<<<< HEAD
                'user_decision' => ['required', 'string', 'max:10'],
            ]);
            $usersAppointment->hours = $hours;
            $usersAppointment->appointment_message = $request->appointment_message;
            $usersAppointment->user_decision = $request->user_decision;
=======
            ]);
            $usersAppointment->hours = $hours;
            $usersAppointment->appointment_message = $request->appointment_message;
>>>>>>> a4aa1a46cbbd1050d9a25b087ae54b28d1a40e8f
            $result = $usersAppointment->update();

            return redirect()->back()->with('success', $result);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UsersAppointment $usersAppointment)
    {
        $appointmentId = $request->id;
        $userId = $request->user_id;
        $schedulerId = $request->scheduler_id;
        $usersAppointment = UsersAppointment::where('id', '=', $appointmentId)->first();
        $result = $usersAppointment->delete();
        // Check delete status
        if ($result) {
            $appointmentDetails = $this->getAppointments($usersAppointment->user_decision, $userId, $schedulerId);
            return redirect()->route('users.show', $userId)->with('success', $appointmentDetails);
        } else {
            return redirect()->route('users.show', $userId)->with('success', $result);
        }
    }
}
