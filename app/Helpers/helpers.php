<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('convertCurrency')) {
    /**
     * Convertir de COP (base) a la moneda del usuario
     */
    function convertCurrency($amountCOP, $toCurrency = null)
    {
        if (!$toCurrency && auth()->check()) {
            $toCurrency = auth()->user()->currency ?? 'USD';
        }
        
        $toCurrency = $toCurrency ?? 'USD';
        
        // Convertir de COP a USD primero (base)
        $amountUSD = $amountCOP / CurrencyHelper::$rates['COP'];
        
        // Luego convertir de USD a la moneda destino
        return CurrencyHelper::convert($amountUSD, $toCurrency);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Formatear monto con símbolo de moneda del usuario
     */
    function formatCurrency($amount, $currency = null, $decimals = 2)
    {
        if (!$currency && auth()->check()) {
            $currency = auth()->user()->currency ?? 'USD';
        }
        
        $currency = $currency ?? 'USD';
        
        return CurrencyHelper::format($amount, $currency, $decimals);
    }
}
