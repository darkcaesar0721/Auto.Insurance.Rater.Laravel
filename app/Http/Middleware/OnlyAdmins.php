<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OnlyAdmins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $admins = config('app.admins');

        if (!Auth::guard($guard)->check() || !in_array(Auth::user()->email, $admins)) {
            return redirect()->back();
        }

        return $next($request);
    }
}
