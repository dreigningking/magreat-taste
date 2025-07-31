<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class Ensure2FAIsVerifiedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->two_factor_enabled) {
            if (!session('2fa_passed')) {
                return redirect()->route('2fa.verify');
            }
        }
        return $next($request);
    }
} 