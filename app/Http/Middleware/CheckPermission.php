<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('permission:manage_users')
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }
        return $next($request);
    }
}