<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PlaceRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'place_rating_id';
    protected $table = "place_ratings";
    protected $fillable = [
        'user_id',
        'place_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id', 'place_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
