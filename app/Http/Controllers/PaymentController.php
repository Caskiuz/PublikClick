<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function selectMethod(Package $package)
    {
        $gateways = PaymentSetting::where('is_active', true)
            ->orderBy('gateway_name')
            ->get()
            ->map(function($setting) {
                return (object)[
                    'id' => $setting->id,
                    'name' => $setting->gateway_name,
                    'type' => $setting->gateway_type,
                    'config' => [
                        'wallet_address' => $setting->wallet_address
                    ]
                ];
            });
        
        return view('payments.select-method', compact('package', 'gateways'));
    }
    
    public function processPayment(Request $request, Package $package)
    {
        $request->validate([
            'gateway_id' => 'required|exists:payment_settings,id'
        ]);
        
        $gateway = PaymentSetting::findOrFail($request->gateway_id);
        
        if (!$gateway->is_active) {
            return back()->with('error', 'Este método de pago no está disponible');
        }
        
        return redirect()->route('payments.checkout', [
            'package' => $package->id,
            'gateway' => $gateway->id
        ]);
    }
    
    public function checkout(Request $request, Package $package)
    {
        $setting = PaymentSetting::findOrFail($request->gateway);
        
        $gateway = (object)[
            'id' => $setting->id,
            'name' => $setting->gateway_name,
            'type' => $setting->gateway_type,
            'config' => [
                'wallet_address' => $setting->wallet_address
            ]
        ];
        
        return view('payments.checkout', compact('package', 'gateway'));
    }
}
