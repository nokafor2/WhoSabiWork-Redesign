<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect user to OAuth provider
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            \Log::error('OAuth Redirect Error (' . $provider . '): ' . $e->getMessage());
            
            return redirect()->route('login')->with('error', [
                'message' => 'Unable to connect to ' . ucfirst($provider) . '. Please try again later.'
            ]);
        }
    }

    /**
     * Handle OAuth provider callback - ENHANCED with multi-provider support
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        try {
            // Get user info from provider
            $socialUser = Socialite::driver($provider)->user();
            $providerEmail = $socialUser->getEmail();
            $providerId = $socialUser->getId();
            
            \Log::info('OAuth Callback (' . $provider . '): User email = ' . $providerEmail . ', ID = ' . $providerId);
            
            // STEP 1: Check if this provider account is already linked to ANY user
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();

            if ($socialAccount) {
                // Provider already linked - just update token and login
                \Log::info('OAuth: Provider account already linked to user ID ' . $socialAccount->user_id);
                
                $socialAccount->update([
                    'provider_token' => $socialUser->token,
                    'provider_email' => $providerEmail,
                    'avatar' => $socialUser->getAvatar(),
                ]);
                
                Auth::login($socialAccount->user, true);
                
                return redirect()->intended('/')->with('success', [
                    'message' => 'Welcome back! Logged in with ' . ucfirst($provider)
                ]);
            }

            // STEP 2: Check if user is already logged in (manual account linking)
            if (Auth::check()) {
                \Log::info('OAuth: User is already logged in, attempting to link provider');
                return $this->linkProviderToLoggedInUser($provider, $socialUser);
            }

            // STEP 3: Try to find user by email (primary or alternate)
            $user = $this->findUserByEmail($providerEmail);

            if ($user) {
                // User found - auto-link this provider to their account
                \Log::info('OAuth: Found existing user with email, auto-linking provider');
                $this->createSocialAccount($user, $provider, $socialUser);
                
                Auth::login($user, true);
                
                return redirect()->intended('/')->with('success', [
                    'message' => 'Linked ' . ucfirst($provider) . ' account and logged in!'
                ]);
            }

            // STEP 4: No user found - create new user account
            \Log::info('OAuth: No existing user found, creating new account');
            $user = $this->createUser($socialUser, $provider);
            $this->createSocialAccount($user, $provider, $socialUser);
            
            Auth::login($user, true);
            
            return redirect()->intended('/')->with('success', [
                'message' => 'Account created and logged in with ' . ucfirst($provider) . '!'
            ]);

        } catch (Exception $e) {
            \Log::error('OAuth Callback Error (' . $provider . '): ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->route('login')->with('error', [
                'message' => 'Authentication failed. Please try again or use email/password login.'
            ]);
        }
    }

    /**
     * Find user by email (checks primary and alternate email)
     *
     * @param string $email
     * @return User|null
     */
    protected function findUserByEmail($email)
    {
        return User::where('email', $email)
            ->orWhere('alternate_email', $email)
            ->first();
    }

    /**
     * Link OAuth provider to currently logged-in user
     *
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @return \Illuminate\Http\Response
     */
    protected function linkProviderToLoggedInUser($provider, $socialUser)
    {
        $user = Auth::user();
        $providerEmail = $socialUser->getEmail();
        
        // Check if this provider is already linked to this user
        $existingLink = $user->socialAccounts()
            ->where('provider', $provider)
            ->first();
        
        if ($existingLink) {
            \Log::info('OAuth: Provider already linked to this user');
            return redirect()->back()->with('info', [
                'message' => ucfirst($provider) . ' is already linked to your account'
            ]);
        }
        
        // Check if provider email is different from user's primary and alternate emails
        if (!$user->ownsEmail($providerEmail)) {
            \Log::info('OAuth: Provider email is different, saving as alternate email');
            
            // Save provider email as alternate email if not already set
            if (!$user->alternate_email) {
                $user->update([
                    'alternate_email' => $providerEmail,
                    // Mark as verified since it's verified by OAuth provider
                    'alternate_email_verified_at' => now(),
                ]);
            }
        }
        
        // Create the social account link
        $this->createSocialAccount($user, $provider, $socialUser);
        
        return redirect()->back()->with('success', [
            'message' => ucfirst($provider) . ' account linked successfully!'
        ]);
    }

    /**
     * Create social account record
     *
     * @param User $user
     * @param string $provider
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @return SocialAccount
     */
    protected function createSocialAccount(User $user, $provider, $socialUser)
    {
        \Log::info('Creating social account link: User ' . $user->id . ' -> ' . $provider);
        
        return SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => $socialUser->token,
            'provider_email' => $socialUser->getEmail(),
            'provider_email_verified' => true, // Verified by OAuth provider
            'avatar' => $socialUser->getAvatar(),
        ]);
    }

    /**
     * Create new user from OAuth provider data
     *
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $provider
     * @return User
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

        // Create the user (no provider fields on user model anymore)
        return User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $socialUser->getEmail(),
            'username' => $username,
            'password' => null, // OAuth users don't have passwords initially
            'email_verified_at' => now(), // Verified by OAuth provider
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
     * Unlink a provider from user account
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function unlinkProvider(Request $request, $provider)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', [
                'message' => 'Please login first'
            ]);
        }
        
        // Safety check: Don't allow unlinking if it's the only login method and no password
        $linkedProvidersCount = $user->socialAccounts()->count();
        
        if (!$user->password && $linkedProvidersCount === 1) {
            return redirect()->back()->with('error', [
                'message' => 'Cannot unlink. This is your only login method. Set a password first or link another provider.'
            ]);
        }
        
        $deleted = $user->socialAccounts()->where('provider', $provider)->delete();
        
        if ($deleted) {
            \Log::info('Unlinked ' . $provider . ' from user ' . $user->id);
            
            return redirect()->back()->with('success', [
                'message' => ucfirst($provider) . ' account unlinked successfully'
            ]);
        }
        
        return redirect()->back()->with('error', [
            'message' => ucfirst($provider) . ' was not linked to your account'
        ]);
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
            $providerEmail = $socialUser->getEmail();
            $providerId = $socialUser->getId();
            
            // Check if this provider account is already linked
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();

            if ($socialAccount) {
                // Update token
                $socialAccount->update([
                    'provider_token' => $token,
                    'provider_email' => $providerEmail,
                ]);
                $user = $socialAccount->user;
            } else {
                // Try to find user by email
                $user = $this->findUserByEmail($providerEmail);

                if ($user) {
                    // Link provider to existing user
                    $this->createSocialAccount($user, $provider, $socialUser);
                } else {
                    // Create new user
                    $user = $this->createUser($socialUser, $provider);
                    $this->createSocialAccount($user, $provider, $socialUser);
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
            \Log::error('API OAuth Error (' . $provider . '): ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
