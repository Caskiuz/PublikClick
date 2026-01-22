<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        $gateways = PaymentSetting::all();
        return view('admin.payment-settings', compact('gateways'));
    }

    public function update(Request $request, PaymentSetting $gateway)
    {
        $data = $request->validate([
            'is_active' => 'boolean',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'webhook_secret' => 'nullable|string',
            'wallet_address' => 'nullable|string',
            'network' => 'nullable|string'
        ]);

        $gateway->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pasarela actualizada correctamente'
        ]);
    }

    public function toggle(PaymentSetting $gateway)
    {
        $gateway->update(['is_active' => !$gateway->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $gateway->is_active,
            'message' => $gateway->is_active ? 'Pasarela activada' : 'Pasarela desactivada'
        ]);
    }
}
