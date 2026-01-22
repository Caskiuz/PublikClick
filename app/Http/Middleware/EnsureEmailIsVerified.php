<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && !$request->user()->email_verified_at) {
            return redirect('/email/verify')->with('message', 'Debes verificar tu email para continuar.');
        }

        return $next($request);
    }
}