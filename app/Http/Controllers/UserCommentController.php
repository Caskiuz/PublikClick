<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCommentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener retiros que requieren comentario
        $pendingComments = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->where('requires_comment', true)
            ->whereNull('user_comment')
            ->with('paymentMethod')
            ->get();
        
        return view('user-comments.index', compact('pendingComments'));
    }

    public function store(Request $request, $transactionId)
    {
        $request->validate([
            'comment' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $user = Auth::user();
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', $user->id)
            ->where('requires_comment', true)
            ->whereNull('user_comment')
            ->firstOrFail();

        $transaction->user_comment = $request->comment;
        $transaction->user_comment_at = now();
        $transaction->metadata = array_merge($transaction->metadata ?? [], [
            'rating' => $request->rating
        ]);
        $transaction->save();

        // Verificar si hay más comentarios pendientes
        $pendingCount = Transaction::where('user_id', $user->id)
            ->where('requires_comment', true)
            ->whereNull('user_comment')
            ->count();

        // Si no hay más comentarios pendientes, desbloquear sistema
        if ($pendingCount == 0) {
            $user->system_locked = false;
            $user->lock_reason = null;
            $user->save();

            return redirect()->route('dashboard')->with('success', '¡Gracias por tu comentario! Tu sistema ha sido desbloqueado.');
        }

        return back()->with('success', 'Comentario guardado. Aún tienes ' . $pendingCount . ' comentario(s) pendiente(s).');
    }
}
