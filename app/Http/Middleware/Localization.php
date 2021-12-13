<?php

namespace App\Http\Middleware;

use App\Helpers\GeneralTrait;
use Closure;

class Localization
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setLocalization();
        return $next($request);
    }
}
