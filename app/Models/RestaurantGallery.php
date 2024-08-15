<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantGallery extends Model
{
    use HasFactory;
    protected $primaryKey = 'res_gallery_id';

    protected $fillable = [
        'image',
        'res_id',
        'status'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'res_id', 'res_id');
    }
}
