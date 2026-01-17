<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EnsureOwnerOrAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('sanctum');
        $task = $request->route('task');

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if ($user->hasRole('admin') || ($task && $task->user_id === $user->id)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
