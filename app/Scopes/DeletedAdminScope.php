<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class DeletedAdminScope implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        // Allow admin to be able to see all the users deleted
        // - Check if the user is currently logged in and if he is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            $builder->withTrashed();
            // $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeleteingScope');
        }
    }
}