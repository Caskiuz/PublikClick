<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function change(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error al cambiar moneda: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la moneda'
            ], 500);
        }
    }
}
