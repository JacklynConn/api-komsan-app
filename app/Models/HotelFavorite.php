<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelFavorite extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id', 
        'user_id',
        'status',
    ];
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
}
