<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n=== USUARIOS EN LA BASE DE DATOS ===\n\n";

$users = DB::table('users')->get();

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Nombre: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Admin: " . ($user->is_admin ? 'Sí' : 'No') . "\n";
    echo "Activo: " . ($user->is_active ? 'Sí' : 'No') . "\n";
    echo "Código Referido: {$user->referral_code}\n";
    echo "Creado: {$user->created_at}\n";
    echo "---\n\n";
}

echo "Total usuarios: " . $users->count() . "\n";
