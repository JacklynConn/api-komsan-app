<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelGallery extends Model
{
    use HasFactory;
    protected $table = 'hotel_galleries';
    protected $primaryKey = 'hotel_gallery_id';
    protected $fillable = [
        'image',
        'status',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

}
