<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function create() {
        return Inertia('SignIn/Index');
    }

    public function store(Request $request) {
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->apiLogin($request);
        }
        
        // Web login (existing logic)
        return $this->webLogin($request);
    }
    
    private function apiLogin(Request $request) {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Try to authenticate with email, username, or phone number
        $user = \App\Models\User::where('email', $credentials['email'])
            ->orWhere('username', $credentials['email'])
            ->orWhere('phone_number', $credentials['email'])
            ->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.'
            ], 401);
        }

        // Check if account is active
        if ($user->account_status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not active. Please contact support.'
            ], 401);
        }

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
    
    private function webLogin(Request $request) {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Try to authenticate with email, username, or phone number
        $user = \App\Models\User::where('email', $credentials['email'])
            ->orWhere('username', $credentials['email'])
            ->orWhere('phone_number', $credentials['email'])
            ->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.'
            ]);
        }

        // Check if account is active
        if ($user->account_status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'Your account is not active. Please contact support.'
            ]);
        }

        // Login the user
        Auth::login($user, $request->boolean('remember'));

        // Regenerate the session
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function destroy(Request $request) {
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->apiLogout($request);
        }
        
        // Web logout
        return $this->webLogout($request);
    }
    
    private function apiLogout(Request $request) {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
    
    private function webLogout(Request $request) {
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
