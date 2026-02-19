<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertisementLike extends Model
{
    protected $fillable = ['user_id', 'advertisement_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(UserAdvertisement::class, 'advertisement_id');
    }
}
