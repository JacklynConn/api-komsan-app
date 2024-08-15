<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceGallery extends Model
{
    use HasFactory;
    protected $table = 'place_galleries';
    protected $primaryKey = 'gallery_id';

    protected $fillable = [
        'place_id',
        'place_image',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id', 'place_id');
    }
}
