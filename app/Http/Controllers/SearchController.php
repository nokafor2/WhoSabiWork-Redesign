<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class SearchController extends Controller
{
    use GlobalFunctions;
    
    public function getState(Request $request)
    {
        // dd($request->selectedOption);

        $selectedOption = $request->selectedOption;

        $states = $this->getBussUserState($selectedOption);

        // return inertia(
        //     'Artisan/Index', 
        //     [ 
        //         'states' => $states,
        //     ]
        // );

        return Inertia::render(
            'Artisan/Index', 
            [
                'states' => $states,
            ]
        );
        // return redirect()->route('users.create')->with('success', 'Account was successfully created!');
        // return redirect()->route('artisans.index')->with('states', $states);

        // session(['states' => $states]);
        // return redirect()->route('artisans.index');
    }
}
