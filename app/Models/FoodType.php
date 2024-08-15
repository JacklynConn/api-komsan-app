<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodType extends Model
{
    use HasFactory;
    protected $table = 'food_types';
    protected $primaryKey = 'food_type_id';
    protected $fillable = [
        'food_type_name'
    ];

    public function foods()
    {
        return $this->hasMany(Food::class, 'food_type_id', 'food_type_id');
    }
}
?>
