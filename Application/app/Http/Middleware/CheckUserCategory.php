<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckUserCategory
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

        if (!$isAdmin && empty(Auth::user()->category)) {
            return redirect('/no-category');
        }

        return $next($request);
    }
}
