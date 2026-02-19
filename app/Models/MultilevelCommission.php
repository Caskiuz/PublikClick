<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultilevelCommission extends Model
{
    protected $fillable = ['user_id', 'from_user_id', 'level', 'amount', 'description'];

    protected $casts = ['amount' => 'decimal:2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
