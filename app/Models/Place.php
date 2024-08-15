<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $primaryKey = 'place_id';
    protected $table = 'places';
    protected $fillable = [
        'village_code',
        'place_name', 
        'place_des', 
        'phone',
        'email',
        'website', 
        'latitude', 
        'longitude', 
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];    

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'village_code');
    }
    public function categoryPlace()
    {
        return $this->hasManyThrough(CategoryPlace::class, PlaceType::class, 'place_id', 'cat_place_id');
    }
    public function placeType(){
        return $this->hasMany(PlaceType::class, 'place_id', 'place_id');
    }
    public function placeGallery(){
        return $this->hasMany(PlaceGallery::class , 'place_id', 'place_id');
    }
    public function placeRatings()
    {
        return $this->hasMany(PlaceRating::class, 'place_id', 'place_id');
    }
}


