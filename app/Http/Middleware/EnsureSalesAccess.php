<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSalesAccess
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->canAccessSales(), 403, 'Tu cuenta no tiene acceso al sistema de ventas.');
        return $next($request);
    }
}
