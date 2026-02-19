<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class CheckPendingComment
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $pendingComment = Transaction::where('user_id', Auth::id())
                ->where('requires_comment', true)
                ->whereNull('user_comment')
                ->where('status', 'completed')
                ->exists();

            if ($pendingComment && !$request->is('comentar-retiro*') && !$request->is('logout')) {
                return redirect()->route('comentar.retiro');
            }
        }

        return $next($request);
    }
}
