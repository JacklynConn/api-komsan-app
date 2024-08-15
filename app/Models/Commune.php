<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $table = 'communes';
    protected $fillable = [
        'commune_code',
        'district_code',
        'province_code',
        'commune_namekh',
        'commune_nameen',
        'status'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'commune_code', 'commune_code');
    }
}
