<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePlace extends Model
{
    use HasFactory;

    protected $primaryKey = 'favorite_place_id';
    protected $fillable = [
        'place_id',
        'user_id',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class , 'place_id', 'place_id');
    }
}
