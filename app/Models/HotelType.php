<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelType extends Model
{
    use HasFactory;
    protected $table = 'hotel_types';
    protected $primaryKey = 'hotel_type_id';
    protected $fillable = [
        'hotel_type_name',
        'status',
    ];

    public function categoryHotel()
    {
        return $this->belongsTo(CategoryHotel::class, 'cat_hotel_id');
    }
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
