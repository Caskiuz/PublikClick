<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();
        $fiat = $gateways->where('type', 'fiat');
        $crypto = $gateways->where('type', 'crypto');
        
        return view('admin.payment-gateways.index', compact('fiat', 'crypto'));
    }

    public function toggle($id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        return back()->with('success', "Método de pago {$gateway->name} " . ($gateway->is_active ? 'activado' : 'desactivado'));
    }

    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        
        $config = [];
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            if ($value) {
                $config[$key] = $value;
            }
        }
        
        $gateway->config = $config;
        $gateway->save();

        return back()->with('success', "Configuración de {$gateway->name} actualizada");
    }
}
