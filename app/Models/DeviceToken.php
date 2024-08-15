<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;
    protected $table = 'device_tokens';
    protected $fillable = [
        'user_id',
        'device_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
