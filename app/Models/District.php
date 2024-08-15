<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $fillable = [
        'district_code',
        'province_code',
        'district_namekh',
        'district_nameen',
        'status'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_code', 'district_code');
    }

    public function villages()
    {
        return $this->hasManyThrough(Village::class, Commune::class, 'district_code', 'commune_code', 'district_code', 'commune_code');
    }
}
