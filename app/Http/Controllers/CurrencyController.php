<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function change(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:USD,COP,EUR,MXN,ARS,BRL,CLP,PEN'
        ]);

        $user = Auth::user();
        $user->currency = $request->currency;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Moneda actualizada correctamente',
            'currency' => $request->currency
        ]);
    }
}
