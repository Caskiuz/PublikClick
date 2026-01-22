<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users';

    public function handle()
    {
        $users = User::all(['id', 'name', 'email']);
        
        if ($users->isEmpty()) {
            $this->info('No hay usuarios en la base de datos');
            return;
        }
        
        $this->info('=== USUARIOS REGISTRADOS ===');
        foreach ($users as $user) {
            $this->line("ID: {$user->id} | Email: {$user->email} | Nombre: {$user->name}");
        }
    }
}
