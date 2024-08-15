<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Defuse\Crypto\Key;


class HotelRating extends Model
{
    use HasFactory;
    protected $primaryKey = 'hotel_rating_id';
    protected $table = "hotel_ratings";

    protected $fillable = [
        'user_id',
        'hotel_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
}
