<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsSuperUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && userHasRole(Auth::user()->permission, array(ADMIN_ROLE, MANAGER_ROLE, CONTRIBUTOR_ROLE))) {
            return $next($request);
        }

        return abort(404);
    }
}
