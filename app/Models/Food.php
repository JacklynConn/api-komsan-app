<?php

namespace App\Models;

use App\Models\FoodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $primaryKey = 'food_id';

    protected $fillable = [
        'food_type_id',
        'res_id',
        'food_name',
        'food_image',
        'food_price'
    ];

    public function foodType()
    {
        return $this->belongsTo(FoodType::class, 'food_type_id', 'food_type_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'res_id', 'res_id');
    }
}
