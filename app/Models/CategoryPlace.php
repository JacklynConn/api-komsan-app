<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPlace extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_place_id';

    protected $fillable = [
        'cat_place_name',
    ];
    
}
