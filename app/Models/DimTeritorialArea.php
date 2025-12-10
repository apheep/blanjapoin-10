<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimTeritorialArea extends Model
{
    use HasFactory;

    protected $table = 'dim_teritorial_area';

    public $timestamps = false;

    protected $fillable = [
        'area',
        'regional',
        'branch',
        'sub_branch',
        'cluster',
        'city',
        'sub_district',
        'village',
        'province',
    ];
}
