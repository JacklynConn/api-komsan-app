<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Defuse\Crypto\Key;

class RestaurantRating extends Model
{
    use HasFactory;
    protected $primaryKey = 'res_rating_id';
    protected $table = "restaurant_ratings";

    protected $fillable = [
        'user_id',
        'res_id',
        'rating',
        'status',
        'comment',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'res_id', 'res_id');
    }
}
