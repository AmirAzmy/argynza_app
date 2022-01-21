<?php

namespace App\Http\Middleware;

use App\Exceptions\NoPermissionException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CheckRole
{
    /**
     * @param $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return mixed
     * @throws NoPermissionException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!in_array(Auth::user()->type, $roles)) {
            throw new NoPermissionException(Lang::get('messages.no_permissions_msg'));
        }
        return $next($request);
    }
}
