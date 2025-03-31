<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsersFeedRequest;
use App\Models\UsersFeedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('ContactUs/Index');
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
    public function store(StoreUsersFeedRequest $request)
    {
        // $firstName = $request->firstName;
        // $lastName = $request->lastName;
        // $phoneNumber = $request->phoneNumber;
        // $email = $request->email;
        // $messageSubject = $request->messageSubject;
        // $messageContent = $request->messageContent;

        // $validate = $request->validated();

        // $result = UsersFeedback::create([
        //     'first_name' => $firstName, 
        //     'last_name' => $lastName,
        //     'phone_number' => $phoneNumber,
        //     'email' => $email,
        //     'message_subject' => $messageSubject,
        //     'message_content' => $messageContent,
        // ]);

        $result = UsersFeedback::create([$request->validated()]);

        // return Inertia('ContactUs/Index')->with('success', 'Message was sent sucessfully.');
        return redirect()->back()->with('success', 'Message was sent sucessfully.');
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
