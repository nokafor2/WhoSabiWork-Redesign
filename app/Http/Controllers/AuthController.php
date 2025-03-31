<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create() {
        return Inertia('SignIn/Index');
    }

    public function store(Request $request) {
        // You can try multiple user login
        if (Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), true)) {
            throw ValidationException::withMessages([
                'email' => 'Authentication failed.'
            ]);
        }

        // If successful, regenerate the session
        $request->session()->regenerate();

        return redirect()->intended('whosabiwork');
    }

    public function destroy(Request $request) {
        // log out
        Auth::logout();
        // Invalidate the session
        $request->session()->invalidate();
        // regenerate token
        $request->session()->regenerateToken();

        // redirect the user to another page
        return redirect()->route('home.index');
    }
}
