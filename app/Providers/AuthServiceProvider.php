<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Address' => 'App\Polices\AddressPolicy',
        'App\User' => 'App\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Set "Remember Me" cookie lifetime to 30 days (43200 minutes)
        // This is the industry standard for "Remember Me" functionality
        // Without "Remember Me", session expires based on SESSION_LIFETIME (default: 2 hours)
        Auth::setRememberDuration(43200); // 30 days * 24 hours * 60 minutes

        // Setting gate authorization for a route
        Gate::define('home.secret', function($user) {
            return $user->is_admin;
        });

        // // This gate checks if the user is able to update the address
        // // Hence, we would check if the user-id is equal to the address-user_id
        // Gate::define('update-address', function($user, $address) {
        //     return $user->id == $address->user_id;
        // });

        // // Set up a gate for deleting a post
        // Gate::define('delete-address', function($user, $address) {
        //     return $user->id == $address->user_id;
        // });

        // // Set override permission for admin
        // Gate::before(function ($user, $ability) {
        //     // Override everything for an admin
        //     // You can also give the admin a list of abilities.
        //     if ($user->is_admin && in_array($ability, ['update-address'])) {
        //         return true;
        //     }
        // });

        // Gate::after(function ($user, $ability, $result) {
        //     if ($user->is_admin) {
        //         return true;
        //     }
        // });

        
        
        /* Using policies */

        // Register the polices for the model 
        // Gate::define('address.update', 'App\Policies\AddressPolicy@update');
        // Gate::define('address.delete', 'App\Policies\AddressPolicy@delete');

        // Using a resource to define all the methods of a policy
        // Gate::resource('addresses', 'App\Policies\AddressPolicy');
        // These methods will be available now
        // addresses.create, addresses.view, addresses.update, addresses.delete

        // Using this gate with a gate resource
        // Gate::before(function ($user, $ability) {
        //     // Override everything for an admin
        //     // You can also give the admin a list of abilities.
        //     if ($user->is_admin && in_array($ability, ['addresses.update'])) {
        //         return true;
        //     }
        // });

        Gate::before(function ($user, $ability) {
            // Override everything for an admin
            // You can also give the admin a list of abilities.
            // if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
            //     return true;
            // }
        });
    }
}
