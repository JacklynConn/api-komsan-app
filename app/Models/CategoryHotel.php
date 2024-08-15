<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryHotel extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_hotel_id';
    protected $fillable = [
        'cat_hotel_name',
    ];

    public function hotelTypes()
    {
        return $this->hasMany(HotelType::class, 'cat_hotel_id');
    }
}
