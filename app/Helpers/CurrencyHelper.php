<?php

namespace App\Helpers;

class CurrencyHelper
{
    // Tasas de cambio base (1 USD = X moneda)
    public static $rates = [
        'USD' => 1,
        'COP' => 4200,      // 1 USD = 4200 COP
        'EUR' => 0.92,      // 1 USD = 0.92 EUR
        'MXN' => 17,        // 1 USD = 17 MXN
        'ARS' => 350,       // 1 USD = 350 ARS
        'BRL' => 5,         // 1 USD = 5 BRL
        'CLP' => 900,       // 1 USD = 900 CLP
        'PEN' => 3.7,       // 1 USD = 3.7 PEN
    ];

    // Símbolos de moneda
    public static $symbols = [
        'USD' => '$',
        'COP' => '$',
        'EUR' => '€',
        'MXN' => '$',
        'ARS' => '$',
        'BRL' => 'R$',
        'CLP' => '$',
        'PEN' => 'S/',
    ];

    /**
     * Convertir de USD a otra moneda
     */
    public static function convert($amountUSD, $toCurrency = 'USD')
    {
        if (!isset(self::$rates[$toCurrency])) {
            return $amountUSD;
        }
        
        return $amountUSD * self::$rates[$toCurrency];
    }

    /**
     * Formatear monto con símbolo de moneda
     */
    public static function format($amount, $currency = 'USD', $decimals = null)
    {
        $symbol = self::$symbols[$currency] ?? '$';
        
        // Si no se especifica decimales, usar 2 por defecto para todas las monedas
        if ($decimals === null) {
            $decimals = 2;
        }
        
        // Asegurar que decimals sea int
        $decimals = (int) $decimals;
        
        return $symbol . number_format($amount, $decimals, '.', ',');
    }

    /**
     * Obtener todas las monedas disponibles
     */
    public static function getAvailableCurrencies()
    {
        return [
            'USD' => 'Dólar Estadounidense (USD)',
            'COP' => 'Peso Colombiano (COP)',
            'EUR' => 'Euro (EUR)',
            'MXN' => 'Peso Mexicano (MXN)',
            'ARS' => 'Peso Argentino (ARS)',
            'BRL' => 'Real Brasileño (BRL)',
            'CLP' => 'Peso Chileno (CLP)',
            'PEN' => 'Sol Peruano (PEN)',
        ];
    }
}
