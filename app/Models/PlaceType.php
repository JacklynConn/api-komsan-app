<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceType extends Model
{
    use HasFactory;
    protected $primaryKey = 'place_type_id';
    protected $table = 'place_types';
    protected $fillable = [
        'cat_place_id',
        'place_id',
    ];

    public function place(){
        return $this->belongsTo(Place::class, 'place_id');
    }
    public function categoryPlace(){
        return $this->belongsTo(CategoryPlace::class, 'cat_place_id');
    }
}
