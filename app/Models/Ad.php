<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url',
        'image_url',
        'click_value',
        'is_active'
    ];

    protected $casts = [
        'click_value' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    // Relaciones
    public function userClicks()
    {
        return $this->hasMany(UserAdClick::class);
    }

    // Métodos de negocio
    public function hasBeenClickedByUser($userId, $date = null)
    {
        $query = $this->userClicks()->where('user_id', $userId);
        
        if ($date) {
            $query->whereDate('clicked_at', $date);
        } else {
            $query->whereDate('clicked_at', today());
        }
        
        return $query->exists();
    }

    public function getTodayClicksCount()
    {
        return $this->userClicks()->whereDate('clicked_at', today())->count();
    }
}
