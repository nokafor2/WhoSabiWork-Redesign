<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create() {
        return Inertia('SignIn/Index');
    }

    public function store(Request $request) {
        // dd($request->all());
        // You can try multiple user login
        // $user = \App\Models\User::where('email', $request->email)->first();
        // dd($user->toArray());

        // dd(Hash::check($request->password, $user->password));
        // if ($user && Hash::check($request->password, $user->password)) {
        //     Auth::login($user);
        // } else {
        //     // Debug here
        //     dd('Login failed');
        // }

        if (!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), true)) {
            throw ValidationException::withMessages([
                'email' => 'Authentication failed.'
            ]);
        }
        // dd(Auth::check());

        // If successful, regenerate the session
        $request->session()->regenerate();

        return redirect()->intended('/');
        // return redirect()->back();
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
