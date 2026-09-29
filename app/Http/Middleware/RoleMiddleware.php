<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Uso en rutas: ->middleware('role:admin')  o  ->middleware('role:admin,registrador')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user('api');

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'No tiene permisos para acceder a este recurso.'
            ], 403);
        }

        return $next($request);
    }
}