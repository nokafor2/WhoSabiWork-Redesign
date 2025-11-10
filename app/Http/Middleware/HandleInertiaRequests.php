<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $userData = null;
        
        if ($request->user()) {
            // Get user avatar from photographs table
            $userAvatar = $request->user()->photographs()
                ->where('photo_type', 'avatar')
                ->latest()
                ->first();
            
            $userData = [
                'id' => $request->user()->id,
                'firstName' => $request->user()->first_name,
                'lastName' => $request->user()->last_name,
                'username' => $request->user()->username,
                'email' => $request->user()->email,
                'account_type' => $request->user()->account_type,
                'account_status' => $request->user()->account_status,
                'avatar' => $userAvatar ? asset('storage/' . $userAvatar->path) : null,
            ];
        }
        
        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'entrepreneur' => $request->session()->get('entrepreneur'),
            'user' => $userData,
        ]);
    }
}
