<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSalesAccess
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->hasRole('Vendedor', 'web'), 403, 'Se requiere el rol Vendedor.');
        return $next($request);
    }
}
