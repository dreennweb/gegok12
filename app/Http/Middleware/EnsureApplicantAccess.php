<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApplicantAccess extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Allow only applicants
        if (auth()->user()->departments()->exists()) {
            abort(403, 'Access denied. This area is for applicants only.');
        }

        return $next($request);
    }
}
