<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireOfficerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session()->has('officer_id')) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        return $next($request);
    }
}
