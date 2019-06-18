<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
            // Load separate dashboards for tenants and normal users (superadmin etc..)
            if(isTenant()) {
                return redirect()->route('tenant.dashboard', ['subdomain' => tenant()->domain]);
            }

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
