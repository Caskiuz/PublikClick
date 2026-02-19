<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAdvertisement extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'file_path', 'file_type', 
        'redirect_url', 'description', 'is_active', 'is_cloned', 
        'cloned_from', 'likes_count', 'comments_count', 'shares_count', 'views_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_cloned' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(AdvertisementLike::class, 'advertisement_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AdvertisementComment::class, 'advertisement_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(AdvertisementShare::class, 'advertisement_id');
    }

    public function clonedFrom(): BelongsTo
    {
        return $this->belongsTo(UserAdvertisement::class, 'cloned_from');
    }

    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
