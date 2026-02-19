<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonioController extends Controller
{
    public function index()
    {
        $testimonios = Transaction::where('status', 'completed')
            ->whereNotNull('user_comment')
            ->with('user')
            ->orderBy('commented_at', 'desc')
            ->paginate(20);

        return view('testimonios-publicos', compact('testimonios'));
    }

    public function showCommentForm()
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('requires_comment', true)
            ->whereNull('user_comment')
            ->where('status', 'completed')
            ->first();

        if (!$transaction) {
            return redirect()->route('dashboard');
        }

        return view('comentar-retiro', compact('transaction'));
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'comment' => 'required|string|min:10|max:1000'
        ]);

        $transaction = Transaction::where('id', $request->transaction_id)
            ->where('user_id', Auth::id())
            ->where('requires_comment', true)
            ->whereNull('user_comment')
            ->first();

        if (!$transaction) {
            return back()->with('error', 'Transacción no encontrada');
        }

        $transaction->user_comment = $request->comment;
        $transaction->commented_at = now();
        $transaction->save();

        return redirect()->route('dashboard')->with('success', '¡Gracias por tu testimonio! Ya puedes continuar usando el sistema.');
    }
}
