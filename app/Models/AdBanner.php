<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdBanner extends Model
{
    protected $fillable = ['title', 'image_path', 'size', 'url', 'views', 'clicks', 'is_active', 'advertiser_id'];

    protected $casts = ['is_active' => 'boolean'];

    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    public static function getRandomBySize($size)
    {
        return self::where('size', $size)->where('is_active', true)->inRandomOrder()->first();
    }
}
