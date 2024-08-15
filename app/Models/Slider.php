<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'tbslider';
    protected $primaryKey = 'idslider';

    protected $fillable = [
        'title','image', 'description', 'type', 'relatedId', 'start_date', 'end_date', 'active_status'
    ];
}
