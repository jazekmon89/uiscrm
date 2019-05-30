<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\OrganisationHelper;

class CMIDataAccess
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
        if (!Auth::user() && OrganisationHelper::getCurrentOrganisationAbbrv() == 'uis') {
            return $next($request);
        }
        return redirect('/home');
    }
}
