<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    protected $primaryKey = 'hotel_id';
    protected $fillable = [
        'hotel_name',
        'village_code',
        'hotel_des',
        'phone',
        'email',
        'latitude',
        'longitude',
        'website',
        'price',
        'status',
    ];

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'village_code');        
    }

    public function categoryHotel()
    {
        return $this->hasManyThrough(CategoryHotel::class, HotelType::class, 'hotel_id', 'cat_hotel_id');
    }
    
    public function hotelTypes()
    {
        return $this->hasMany(HotelType::class, 'hotel_id');
    }

    public function hotelGallery()
    {
        return $this->hasMany(HotelGallery::class, 'hotel_id');
    }
    public function favorites()
    {
        return $this->hasMany(HotelFavorite::class, 'hotel_id', 'user_id');
    }
    public function hotelRatings()
    {
        return $this->hasMany(HotelRating::class, 'hotel_id');
    }
    
}
