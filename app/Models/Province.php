<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces';
    protected $fillable = [
        'province_code',
        'province_namekh',
        'province_nameen',
        'province_image',
        'status'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'province_code');
    }

    public function communes()
    {
        return $this->hasManyThrough(Commune::class, District::class, 'province_code', 'district_code', 'province_code', 'district_code');
    }

    public function villages()
    {
        return $this->hasManyThrough(Village::class, Commune::class, 'province_code', 'commune_code', 'province_code', 'commune_code');
    }
}
