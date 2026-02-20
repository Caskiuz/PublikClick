<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemLock
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && $user->system_locked) {
            // Permitir acceso solo a rutas de comentarios y logout
            if (!$request->routeIs('user-comments.*') && !$request->routeIs('logout')) {
                return redirect()->route('user-comments.index')
                    ->with('warning', $user->lock_reason ?? 'Tu sistema está bloqueado. Debes completar las acciones pendientes.');
            }
        }
        
        return $next($request);
    }
}
