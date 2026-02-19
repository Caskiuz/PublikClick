<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('formatCurrency')) {
    /**
     * Formatear monto - asume que el valor viene en COP
     */
    function formatCurrency($amountCOP, $currency = null, $decimals = null)
    {
        $user = auth()->user();
        if (!$currency) {
            $currency = $user ? ($user->currency ?? 'COP') : 'COP';
        }
        
        // Si el usuario quiere ver en COP, mostrar directo
        if ($currency === 'COP') {
            return CurrencyHelper::format($amountCOP, 'COP', $decimals);
        }
        
        // Convertir de COP a la moneda deseada
        $amountUSD = $amountCOP / CurrencyHelper::$rates['COP']; // COP a USD
        $convertedAmount = CurrencyHelper::convert($amountUSD, $currency);
        return CurrencyHelper::format($convertedAmount, $currency, $decimals);
    }
}

if (!function_exists('convertCurrency')) {
    /**
     * Convertir monto de COP a la moneda del usuario
     */
    function convertCurrency($amountCOP, $toCurrency = null)
    {
        if (!$toCurrency) {
            $user = auth()->user();
            $toCurrency = $user ? ($user->currency ?? 'COP') : 'COP';
        }
        
        if ($toCurrency === 'COP') {
            return $amountCOP;
        }
        
        // Convertir de COP a USD primero, luego a la moneda deseada
        $amountUSD = $amountCOP / CurrencyHelper::$rates['COP'];
        return CurrencyHelper::convert($amountUSD, $toCurrency);
    }
}

if (!function_exists('getCurrencySymbol')) {
    /**
     * Obtener símbolo de la moneda del usuario
     */
    function getCurrencySymbol($currency = null)
    {
        if (!$currency) {
            $user = auth()->user();
            $currency = $user ? ($user->currency ?? 'COP') : 'COP';
        }
        
        return CurrencyHelper::$symbols[$currency] ?? '$';
    }
}
