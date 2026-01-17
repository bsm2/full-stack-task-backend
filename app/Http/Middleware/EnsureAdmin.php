<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('sanctum');
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
