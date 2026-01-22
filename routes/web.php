<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClickController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/welcome', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/anuncios', [DashboardController::class, 'anuncios'])->name('anuncios');
    Route::get('/referidos', [DashboardController::class, 'referidos'])->name('referidos');
    Route::get('/paquetes', [DashboardController::class, 'paquetes'])->name('paquetes');
    Route::get('/billetera', [DashboardController::class, 'billetera'])->name('billetera');
    Route::get('/estadisticas', [DashboardController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/configuracion', [DashboardController::class, 'configuracion'])->name('configuracion');
    
    // Rutas de clicks
    Route::prefix('clicks')->name('clicks.')->group(function () {
        Route::get('/', [ClickController::class, 'index'])->name('index');
        Route::post('/main/{ad}', [ClickController::class, 'clickMainAd'])->name('main');
        Route::post('/mini', [ClickController::class, 'clickMiniAd'])->name('mini');
        Route::post('/mega', [ClickController::class, 'clickMegaAd'])->name('mega');
        Route::get('/stats', [ClickController::class, 'getClickStats'])->name('stats');
    });
    
    // Rutas de paquetes
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/available', [PackageController::class, 'getAvailable'])->name('available');
        Route::get('/user', [PackageController::class, 'getUserPackage'])->name('user');
        Route::get('/{package}', [PackageController::class, 'show'])->name('show');
        Route::post('/{package}/purchase', [PackageController::class, 'purchase'])->name('purchase');
    });
    
    // Rutas de retiros
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::post('/request', [WithdrawalController::class, 'request'])->name('request');
        Route::post('/{transaction}/cancel', [WithdrawalController::class, 'cancel'])->name('cancel');
        Route::get('/history', [WithdrawalController::class, 'history'])->name('history');
    });
});

// Rutas administrativas
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/withdrawals', [AdminController::class, 'withdrawals']);
    Route::post('/withdrawals/{transaction}/approve', [AdminController::class, 'approveWithdrawal']);
    Route::post('/withdrawals/{transaction}/reject', [AdminController::class, 'rejectWithdrawal']);
    Route::get('/reports', [AdminController::class, 'reports']);
});

Route::get('/test', function () {
    return 'PubliClick System - Laravel 11 funcionando correctamente';
});