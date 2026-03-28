<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session()->has('admin_id')) {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        return $next($request);
    }
}
