<?php

namespace App\Http\Middleware;

use Closure;

class Book
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::guard('agent')->check() && !\Auth::guard('book')->check())
            return redirect('/agent/login/login');
        return $next($request);
    }
}
