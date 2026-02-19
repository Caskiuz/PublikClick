<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAdvertisement
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Verificar si tiene publicidad PTC activa
        $hasPtc = $user->advertisements()
            ->where('type', 'ptc')
            ->where('is_active', true)
            ->exists();

        // Verificar si tiene publicidad Banner activa
        $hasBanner = $user->advertisements()
            ->where('type', 'banner')
            ->where('is_active', true)
            ->exists();

        if (!$hasPtc || !$hasBanner) {
            return redirect()->route('advertisements.create-ptc')
                ->with('error', 'Debes crear tu publicidad PTC y Banner antes de realizar tareas');
        }

        return $next($request);
    }
}
