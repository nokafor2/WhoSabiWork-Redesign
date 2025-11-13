<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth page
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', [
                'message' => 'Unable to connect to ' . ucfirst($provider) . '. Please try again later.'
            ]);
        }
    }

    /**
     * Handle callback from Google OAuth
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        try {
            // Get user info from provider
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists with this provider
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($user) {
                // User exists with this provider, update token and login
                $user->update([
                    'provider_token' => $socialUser->token,
                ]);
            } else {
                // Check if user exists with this email
                $existingUser = User::where('email', $socialUser->getEmail())->first();

                if ($existingUser) {
                    // Link OAuth to existing account
                    $existingUser->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                    ]);
                    $user = $existingUser;
                } else {
                    // Create new user
                    $user = $this->createUser($socialUser, $provider);
                }
            }

            // Log the user in
            Auth::login($user, true);

            // Redirect to intended page or home
            return redirect()->intended('/')->with('success', [
                'message' => 'Successfully logged in with ' . ucfirst($provider) . '!'
            ]);

        } catch (Exception $e) {
            \Log::error('OAuth Error: ' . $e->getMessage());
            
            return redirect()->route('login')->with('error', [
                'message' => 'Authentication failed. Please try again or use email/password login.'
            ]);
        }
    }

    /**
     * Create a new user from OAuth provider data
     *
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $provider
     * @return \App\Models\User
     */
    protected function createUser($socialUser, $provider)
    {
        // Split name into first and last name
        $nameParts = explode(' ', $socialUser->getName() ?? 'User Name', 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Generate a unique username
        $baseUsername = Str::slug($firstName . '-' . $lastName);
        $username = $this->generateUniqueUsername($baseUsername);

        // Create the user
        return User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $socialUser->getEmail(),
            'username' => $username,
            'password' => null, // OAuth users don't have passwords
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => $socialUser->token,
            'email_verified_at' => now(), // OAuth emails are verified by provider
            'account_type' => 'regular',
            'account_status' => 'active',
        ]);
    }

    /**
     * Generate a unique username
     *
     * @param string $baseUsername
     * @return string
     */
    protected function generateUniqueUsername($baseUsername)
    {
        $username = $baseUsername;
        $counter = 1;

        // Keep appending numbers until we find a unique username
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Handle API OAuth callback
     * 
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiCallback(Request $request, $provider)
    {
        try {
            // For API, the frontend sends the access token
            $token = $request->input('access_token');
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token is required'
                ], 400);
            }

            // Get user info from provider using the token
            $socialUser = Socialite::driver($provider)->userFromToken($token);
            
            // Check if user exists with this provider
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($user) {
                // Update token
                $user->update(['provider_token' => $token]);
            } else {
                // Check if email exists
                $existingUser = User::where('email', $socialUser->getEmail())->first();

                if ($existingUser) {
                    // Link OAuth to existing account
                    $existingUser->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $token,
                    ]);
                    $user = $existingUser;
                } else {
                    // Create new user
                    $user = $this->createUser($socialUser, $provider);
                }
            }

            // Create Sanctum token for API authentication
            $apiToken = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'account_type' => $user->account_type,
                    'account_status' => $user->account_status,
                ],
                'token' => $apiToken
            ]);

        } catch (Exception $e) {
            \Log::error('API OAuth Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }
}

