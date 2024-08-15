<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $table = 'villages';
    protected $fillable = [
        'village_code',
        'commune_code',
        'district_code',
        'province_code',
        'village_namekh',
        'village_nameen',
        'status'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_code', 'commune_code');
    }
}
