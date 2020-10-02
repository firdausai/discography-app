<?php

namespace App\Http\Middleware;

use Closure;

class checkAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('login')) {
            if (session()->has('authorize')) {
                return redirect('dashboard');
            } else {
                return $next($request);
            }
        } else if ($request->is('dashboard')) {
            if (session()->has('authorize')) {
                return $next($request);
            } else {
                return redirect('/login');
            }
        }
    }
}
