<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;    protected $primaryKey = 'res_id';

    protected $fillable = [
        'village_code',
        'res_name',
        'res_des',
        'latitude',
        'longitude',
        'res_phone',
        'res_email',
        'res_web',
        'open_time',
        'close_time',
        'status'
    ];

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'village_code');
    }

    public function foods()
    {
        return $this->hasMany(Food::class, 'res_id', 'res_id');
    }

    public function restaurantGallery()
    {
        return $this->hasMany(RestaurantGallery::class, 'res_id', 'res_id');
    }

    public function resRating(){
        return $this->hasMany(RestaurantRating::class, 'res_id', 'res_id');
    }
}

?>
