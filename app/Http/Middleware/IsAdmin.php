<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth; // <-- Add this line

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->account_type === 'admin'){
            return $next($request);
        }
        abort(403, 'Unauthorized.');
    }
}
