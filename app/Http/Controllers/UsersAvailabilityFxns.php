<?php

namespace App\Http\Controllers;

use App\Http\Traits\GlobalFunctions;
use Illuminate\Http\Request;

class UsersAvailabilityFxns extends Controller
{
    use GlobalFunctions;

    public function availabilityDates(Request $request) {
        $userId = $request->userId;
        $availabilityDates = $this->getAvailabilityDates($userId);

        return redirect()->back()->with('success', $availabilityDates);
    }
}
