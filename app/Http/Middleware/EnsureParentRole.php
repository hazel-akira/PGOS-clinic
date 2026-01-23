<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureParentRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/parent/login');
        }

        $user = auth()->user();

        // Allow users with parent role or users linked to a guardian
        if (!$user->hasRole('parent') && !$user->guardian) {
            abort(403, 'Unauthorized. Parent access required.');
        }

        return $next($request);
    }
}
