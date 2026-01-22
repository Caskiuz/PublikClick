<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearUserCache extends Command
{
    protected $signature = 'cache:clear-users {--user= : Clear cache for specific user}';
    protected $description = 'Clear user dashboard cache';

    public function handle()
    {
        if ($userId = $this->option('user')) {
            Cache::forget('user_dashboard_' . $userId);
            $this->info("Cache cleared for user {$userId}");
        } else {
            // Limpiar todos los caches de usuarios
            $pattern = 'user_dashboard_*';
            $keys = Cache::getRedis()->keys($pattern);
            foreach ($keys as $key) {
                Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
            }
            $this->info('All user dashboard caches cleared');
        }
        
        return 0;
    }
}