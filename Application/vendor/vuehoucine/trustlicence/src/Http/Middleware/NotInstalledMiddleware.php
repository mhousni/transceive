<?php

namespace Vuehoucine\Trustlicence\Http\Middleware;

use Closure;

class NotInstalledMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!env('VIRONEER_SYSTEMSTATUS')) {return redirect()->route('install.index');}
        return $next($request);
    }
}
