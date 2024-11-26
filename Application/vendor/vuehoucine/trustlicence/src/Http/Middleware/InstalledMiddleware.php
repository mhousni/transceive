<?php

namespace Vuehoucine\Trustlicence\Http\Middleware;

use Closure;

class InstalledMiddleware
{
    public function handle($request, Closure $next)
    {
        if (env('VIRONEER_SYSTEMSTATUS')) {return redirect('/');}
        return $next($request);
    }
}
