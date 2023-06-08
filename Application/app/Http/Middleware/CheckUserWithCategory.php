<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckUserWithCategory
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
        $isAdmin = userHasRole(Auth::user()->permission, array(ADMIN_ROLE));

        if ($isAdmin || !empty(Auth::user()->category)) {
            return redirect('/home');
        }

        return $next($request);
    }
}
