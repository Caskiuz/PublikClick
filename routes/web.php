<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClickController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ReferralController;

Route::get('/', function () {
    return view('landing-figma');
});

Route::get('/demo', function () {
    return view('landing-new');
})->name('demo');

Route::get('/welcome', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function() {
    // Validar que venga con código de referido
    if (!request()->has('ref')) {
        return view('auth.register-new')->with('error', 'Necesitas un código de referido para registrarte');
    }
    return view('auth.register-new');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Rutas de testimonios
Route::get('/comentar-retiro', [App\Http\Controllers\TestimonioController::class, 'showCommentForm'])->name('comentar.retiro');
Route::post('/comentar-retiro', [App\Http\Controllers\TestimonioController::class, 'storeComment'])->name('testimonio.store');

// Dashboard (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mis Tareas
    Route::get('/anuncios', [DashboardController::class, 'anuncios'])->name('anuncios')->middleware('check.advertisement');
    Route::get('/mini-anuncios', [App\Http\Controllers\AdController::class, 'miniAds'])->name('mini-anuncios')->middleware('check.advertisement');
    Route::get('/mega-anuncios', [App\Http\Controllers\AdController::class, 'megaAds'])->name('mega-anuncios')->middleware('check.advertisement');
Route::get('/anuncios-principales', [App\Http\Controllers\AdController::class, 'principalAds'])->name('anuncios-principales')->middleware('check.advertisement');
Route::post('/ads/process-click', [App\Http\Controllers\AdController::class, 'processClick'])->name('ads.process')->middleware('check.advertisement');
Route::get('/api/captcha/generate', [App\Http\Controllers\AdController::class, 'generateCaptcha'])->name('captcha.generate');
    
    // Navegación principal
    Route::get('/tablero', [DashboardController::class, 'tablero'])->name('tablero');
    Route::get('/referidos', [ReferralController::class, 'index'])->name('referidos');
    Route::get('/lider', [DashboardController::class, 'lider'])->name('lider');
    Route::get('/calculadora', function() { return view('calculadora'); })->name('calculadora');
    Route::get('/billetera', [App\Http\Controllers\WalletController::class, 'index'])->name('billetera');
Route::post('/billetera/retiro', [App\Http\Controllers\WalletController::class, 'requestWithdrawal'])->name('wallet.withdraw');
Route::get('/historial-retiros', [App\Http\Controllers\WalletController::class, 'historialRetiros'])->name('historial-retiros');
    
    // Comunidad
    Route::get('/amigos', [App\Http\Controllers\SocialController::class, 'index'])->name('social.feed');
    Route::get('/social/friends', [App\Http\Controllers\SocialController::class, 'friends'])->name('social.friends');
    Route::get('/social/search', [App\Http\Controllers\SocialController::class, 'searchUsers'])->name('social.search');
    Route::post('/social/post', [App\Http\Controllers\SocialController::class, 'storePost'])->name('social.post.store');
    Route::post('/social/post/{id}/like', [App\Http\Controllers\SocialController::class, 'likePost'])->name('social.post.like');
    Route::post('/social/post/{id}/comment', [App\Http\Controllers\SocialController::class, 'commentPost'])->name('social.post.comment');
    Route::post('/social/friend/{userId}/send', [App\Http\Controllers\SocialController::class, 'sendFriendRequest'])->name('social.friend.send');
    Route::post('/social/friend/{id}/accept', [App\Http\Controllers\SocialController::class, 'acceptFriendRequest'])->name('social.accept');
    Route::post('/social/friend/{id}/reject', [App\Http\Controllers\SocialController::class, 'rejectFriendRequest'])->name('social.reject');
    Route::get('/social/chat/{friendId}', [App\Http\Controllers\SocialController::class, 'chat'])->name('social.chat');
    Route::post('/social/message/{friendId}', [App\Http\Controllers\SocialController::class, 'sendMessage'])->name('social.message.send');
    Route::get('/testimonios', [DashboardController::class, 'testimonios'])->name('testimonios');
    Route::get('/proyectos-donaciones', [DashboardController::class, 'proyectosDonaciones'])->name('proyectos-donaciones');
    Route::get('/sube-proyecto', [DashboardController::class, 'subeProyecto'])->name('sube-proyecto');
    
    // Paquetes
    Route::get('/paquetes', [DashboardController::class, 'paquetes'])->name('paquetes');
    Route::get('/mis-paquetes', [DashboardController::class, 'misPaquetes'])->name('mis-paquetes');
    
    // Administración de Publicaciones
    Route::get('/crear-ptc', [App\Http\Controllers\AdvertisementController::class, 'createPtc'])->name('advertisements.create-ptc');
    Route::get('/crear-banner', [App\Http\Controllers\AdvertisementController::class, 'createBanner'])->name('advertisements.create-banner');
    Route::post('/advertisements/store', [App\Http\Controllers\AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::post('/advertisements/preview', [App\Http\Controllers\AdvertisementController::class, 'preview'])->name('advertisements.preview');
    Route::post('/advertisements/{id}/clone', [App\Http\Controllers\AdvertisementController::class, 'clone'])->name('advertisements.clone');
    Route::post('/advertisements/{id}/like', [App\Http\Controllers\AdvertisementController::class, 'like'])->name('advertisements.like');
    Route::post('/advertisements/{id}/comment', [App\Http\Controllers\AdvertisementController::class, 'comment'])->name('advertisements.comment');
    Route::post('/advertisements/{id}/share', [App\Http\Controllers\AdvertisementController::class, 'share'])->name('advertisements.share');
    Route::get('/advertisements/{id}/comments', [App\Http\Controllers\AdvertisementController::class, 'getComments'])->name('advertisements.comments');
    
    // Recomienda y Gana
    Route::get('/recomienda-gana', [DashboardController::class, 'recomiendaGana'])->name('recomienda-gana');
    
    // Mundo Sorteos
    Route::get('/mundo-sorteos', [DashboardController::class, 'mundoSorteos'])->name('mundo-sorteos');
    
    // Multinivel
    Route::get('/multinivel', [DashboardController::class, 'multinivel'])->name('multinivel');
    
    // Otras rutas existentes
    Route::get('/billetera', [DashboardController::class, 'billetera'])->name('billetera');
    Route::get('/estadisticas', [DashboardController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    Route::get('/configuracion', [DashboardController::class, 'configuracion'])->name('configuracion');
    
    // Rutas de clicks
    Route::prefix('clicks')->name('clicks.')->group(function () {
        Route::get('/', [ClickController::class, 'index'])->name('index');
        Route::get('/view/{type}/{ad}', [ClickController::class, 'viewAd'])->name('view.ad');
        Route::post('/{type}/{ad}/validate', [ClickController::class, 'validateView'])->name('validate');
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
    
    // Rutas de pagos
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/{package}/select-method', [PaymentController::class, 'selectMethod'])->name('select-method');
        Route::post('/{package}/process', [PaymentController::class, 'processPayment'])->name('process');
        Route::get('/{package}/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    });
    
    // Rutas de retiros
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::post('/request', [WithdrawalController::class, 'request'])->name('request');
        Route::post('/{transaction}/cancel', [WithdrawalController::class, 'cancel'])->name('cancel');
        Route::get('/history', [WithdrawalController::class, 'history'])->name('history');
    });
    
    // Comentarios de usuario sobre retiros
    Route::get('/user-comments', [\App\Http\Controllers\UserCommentController::class, 'index'])->name('user-comments.index');
    Route::post('/user-comments/{transaction}', [\App\Http\Controllers\UserCommentController::class, 'store'])->name('user-comments.store');
    
    // Ruta de cambio de moneda
    Route::post('/currency/change', [CurrencyController::class, 'change'])->name('currency.change');
    
    // Nequi withdrawals
    Route::post('/nequi/withdrawal', [\App\Http\Controllers\NequiController::class, 'requestWithdrawal'])->name('nequi.withdrawal');
    Route::post('/nequi/save-phone', [\App\Http\Controllers\NequiController::class, 'savePhone'])->name('nequi.save-phone');
});

// Rutas administrativas
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{transaction}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{transaction}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    
    // Payment Settings
    Route::get('/payment-settings', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'index'])->name('payment-settings');
    Route::put('/payment-settings/{gateway}', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'update'])->name('payment-settings.update');
    Route::post('/payment-settings/{gateway}/toggle', [\App\Http\Controllers\Admin\PaymentSettingsController::class, 'toggle'])->name('payment-settings.toggle');
    
    // Withdrawals Management
    Route::get('/withdrawals-management', [\App\Http\Controllers\Admin\WithdrawalManagementController::class, 'index'])->name('withdrawals.management');
    Route::post('/withdrawals/{id}/approve', [\App\Http\Controllers\Admin\WithdrawalManagementController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [\App\Http\Controllers\Admin\WithdrawalManagementController::class, 'reject'])->name('withdrawals.reject');
    Route::post('/withdrawals/{id}/upload-proof', [\App\Http\Controllers\Admin\WithdrawalManagementController::class, 'uploadProof'])->name('withdrawals.upload-proof');
    
    // Banners
    Route::get('/banners', [\App\Http\Controllers\AdBannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [\App\Http\Controllers\AdBannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [\App\Http\Controllers\AdBannerController::class, 'store'])->name('banners.store');
    Route::post('/banners/{id}/toggle', [\App\Http\Controllers\AdBannerController::class, 'toggle'])->name('banners.toggle');
    Route::delete('/banners/{id}', [\App\Http\Controllers\AdBannerController::class, 'destroy'])->name('banners.destroy');
    
    // Payment Gateways
    Route::get('/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::post('/payment-gateways/{id}/toggle', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'toggle'])->name('payment-gateways.toggle');
    Route::put('/payment-gateways/{id}', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
    
    // Site Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'update'])->name('settings.update');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/toggle', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggle'])->name('users.toggle');
});

Route::get('/test', function () {
    return 'PubliClick System - Laravel 11 funcionando correctamente';
});

// Banner tracking
Route::post('/banners/{id}/view', [\App\Http\Controllers\AdBannerController::class, 'trackView'])->name('banners.view');
Route::get('/banners/{id}/click', [\App\Http\Controllers\AdBannerController::class, 'trackClick'])->name('banners.click');