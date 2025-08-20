<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EntrepreneurController extends Controller
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
    public function show(Request $request, $id)
    {
        // Debug: Check if user is authenticated
        $currentUser = $request->user();
        Log::info('EntrepreneurController::show - Current user:', [
            'authenticated' => $currentUser ? true : false,
            'user_id' => $currentUser ? $currentUser->id : null,
            'viewing_entrepreneur_id' => $id
        ]);

        // Check if user has a business account
        $foundUser = User::find($id);
        if ($foundUser->account_type === 'business') {
            $foundUser = $this->getUserDetails($id);
            // dd($foundUser);
            return Inertia('Entrepreneur/Index', ['entrepreneur' => $foundUser]);
        } else {
            return Inertia('Index/Index');
            // return redirect()->route('notfound');
        }
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
