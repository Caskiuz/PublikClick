<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentSetting;

class PaymentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            // FIAT
            ['gateway_name' => 'Stripe', 'gateway_type' => 'fiat', 'is_active' => false],
            ['gateway_name' => 'PayPal', 'gateway_type' => 'fiat', 'is_active' => false],
            ['gateway_name' => 'Nequi', 'gateway_type' => 'fiat', 'is_active' => true],
            
            // CRYPTO
            ['gateway_name' => 'Bitcoin (BTC)', 'gateway_type' => 'crypto', 'is_active' => false, 'network' => 'BTC'],
            ['gateway_name' => 'USDT (TRC20)', 'gateway_type' => 'crypto', 'is_active' => false, 'network' => 'TRC20'],
            ['gateway_name' => 'USDT (ERC20)', 'gateway_type' => 'crypto', 'is_active' => false, 'network' => 'ERC20'],
            ['gateway_name' => 'Binance Pay', 'gateway_type' => 'crypto', 'is_active' => false],
            ['gateway_name' => 'Coinbase Commerce', 'gateway_type' => 'crypto', 'is_active' => false],
        ];

        foreach ($gateways as $gateway) {
            PaymentSetting::create($gateway);
        }
    }
}
