<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificamos si hay usuario logueado
        if (! $request->user()) {
             return redirect('/login');
        }

        // 2. Verificamos si tiene el rol requerido usando tu función hasRole()
        // Esta función la definimos previamente en tu modelo User.php
        if (! $request->user()->hasRole($role)) {
            // Si no tiene el rol, detenemos todo y mostramos error 403 (Prohibido)
            abort(403, 'No tienes permiso para acceder a esta zona.');
        }

        // 3. Si pasó las pruebas, dejamos que la petición continúe
        return $next($request);
    }
}