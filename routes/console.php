<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Programar generación de anuncios diarios a las 12:00 AM
Schedule::command('ads:generate-daily')->dailyAt('00:00');

// Actualizar rangos de usuarios diariamente
Schedule::command('ranks:update')->dailyAt('00:05');

// Procesar comisiones de referidos diariamente
Schedule::command('commissions:process')->dailyAt('00:10');
