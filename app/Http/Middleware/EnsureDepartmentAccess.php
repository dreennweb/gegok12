<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDepartmentAccess extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Allow only department staff/executives
        if (!auth()->user()->departments()->exists()) {
            abort(403, 'Access denied. This area is for department staff only.');
        }

        return $next($request);
    }
}
