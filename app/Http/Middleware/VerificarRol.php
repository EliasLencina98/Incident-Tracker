<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        //Restringe acciones segun el rol (tabla users)
        if (!in_array($request->user()?->rol, $roles, true)) {
            abort(403, 'No tiene permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
