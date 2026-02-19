<?php

namespace App\Services;

class EconomicConfig
{
    // VALORES DE ANUNCIOS PRINCIPALES (COP)
    const MAIN_AD_VALUES = [
        25 => 400,   // Paquete $25 = $400 COP retiro
        50 => 600,   // Paquete $50 = $600 COP retiro
        100 => 1120, // Paquete $100 = $1,120 COP retiro
        150 => 1600  // Paquete $150 = $1,600 COP retiro
    ];

    // VALORES DE MINI-ANUNCIOS (COP)
    const MINI_AD_VALUES = [
        25 => 83.33,  // Paquete $25 = $83.33 COP
        50 => 425,    // Paquete $50 = $425 COP
        100 => 100,   // Paquete $100 = $100 COP
        150 => 600    // Paquete $150 = $600 COP
    ];

    // CANTIDAD DE MINI-ANUNCIOS POR PAQUETE
    const MINI_AD_COUNT = [
        25 => 4,   // 4 mini-anuncios diarios
        50 => 4,   // 4 mini-anuncios diarios
        100 => 4,  // 4 mini-anuncios diarios
        150 => 8   // 8 mini-anuncios diarios
    ];

    // CONSTANTES GENERALES
    const MAIN_ADS_DAILY = 5;           // 5 anuncios principales por día
    const MAIN_AD_DONATION = 10;        // $10 COP van a donaciones
    const MEGA_AD_VALUE = 2000;         // $2,000 COP por mega-anuncio
    const UNLOCKED_MINI_VALUE = 100;    // $100 COP por mini desbloqueado

    // MEGA-ANUNCIOS POR PAQUETE DEL REFERIDO
    const MEGA_ADS_BY_PACKAGE = [
        25 => 5,    // 5 mega-anuncios ($10,000)
        50 => 10,   // 10 mega-anuncios ($20,000)
        100 => 20,  // 20 mega-anuncios ($40,000)
        150 => 30   // 30 mega-anuncios ($60,000)
    ];

    // COMISIONES POR RANGO (COP)
    const RANK_COMMISSIONS = [
        'Jade' => 100,
        'Perla' => 200,
        'Zafiro' => 300,
        'Rubí' => 400,
        'Esmeralda' => 400,
        'Diamante' => 400,
        'Diamante Azul' => 400,
        'Diamante Negro' => 400,
        'Diamante Corona' => 400
    ];

    // MINI-ANUNCIOS DESBLOQUEADOS POR RANGO
    const UNLOCKED_MINIS_BY_RANK = [
        'Jade' => 1,
        'Perla' => 2,
        'Zafiro' => 3,
        'Rubí' => 4,
        'Esmeralda' => 5,
        'Diamante' => 5,
        'Diamante Azul' => 5,
        'Diamante Negro' => 5,
        'Diamante Corona' => 5
    ];

    // COMISIONES MULTINIVEL (Niveles 2-6)
    const MULTILEVEL_COMMISSIONS = [
        2 => 20,  // Nivel 2: $20 COP
        3 => 30,  // Nivel 3: $30 COP
        4 => 40,  // Nivel 4: $40 COP
        5 => 50,  // Nivel 5: $50 COP
        6 => 60   // Nivel 6: $60 COP
    ];

    // MÉTODOS ESTÁTICOS
    public static function getMainAdValue(int $packagePrice): float
    {
        return self::MAIN_AD_VALUES[$packagePrice] ?? 400;
    }

    public static function getMiniAdValue(int $packagePrice): float
    {
        return self::MINI_AD_VALUES[$packagePrice] ?? 83.33;
    }

    public static function getMiniAdCount(int $packagePrice): int
    {
        return self::MINI_AD_COUNT[$packagePrice] ?? 4;
    }

    public static function getMegaAdsCount(int $packagePrice): int
    {
        return self::MEGA_ADS_BY_PACKAGE[$packagePrice] ?? 5;
    }

    public static function getRankCommission(string $rankName): float
    {
        return self::RANK_COMMISSIONS[$rankName] ?? 100;
    }

    public static function getUnlockedMinisCount(string $rankName): int
    {
        return self::UNLOCKED_MINIS_BY_RANK[$rankName] ?? 1;
    }

    public static function getMultilevelCommission(int $level): float
    {
        return self::MULTILEVEL_COMMISSIONS[$level] ?? 0;
    }

    // CÁLCULOS
    public static function calculateMonthlyEarnings(int $packagePrice): array
    {
        $mainValue = self::getMainAdValue($packagePrice);
        $miniValue = self::getMiniAdValue($packagePrice);
        $miniCount = self::getMiniAdCount($packagePrice);

        $mainRetiro = $mainValue * self::MAIN_ADS_DAILY * 30;
        $mainDonacion = self::MAIN_AD_DONATION * self::MAIN_ADS_DAILY * 30;
        $miniRetiro = $miniValue * $miniCount * 30;

        return [
            'retiro' => $mainRetiro + $miniRetiro,
            'donacion' => $mainDonacion,
            'total' => $mainRetiro + $miniRetiro + $mainDonacion
        ];
    }
}
