<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperadminAccess extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Allow only superadmin
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Access denied. Superadmin only.');
        }

        return $next($request);
    }
}
