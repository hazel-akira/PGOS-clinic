<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureParentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/parent/login');
        }

        $user = auth()->user();
        
        // Allow parent/guardian role and admin role
        if (!$user->hasAnyRole(['parent'])) {
            abort(403, 'Unauthorized. Parent/Guardian or Admin access required.');
        }

        return $next($request);
    }
}
