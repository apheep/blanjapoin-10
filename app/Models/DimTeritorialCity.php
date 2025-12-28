<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimTeritorialCity extends Model
{
    use HasFactory;

    protected $table = 'dim_teritorial_city';

    protected $primaryKey = 'city';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'area',
        'regional',
        'branch',
        'sub_branch',
        'cluster',
        'city',
        'province',
        'archetype',
        'zone_package_acquisition',
        'zone_package_core',
        'zone_package_newvf',
        'zone_package_combosakti',
        'zone_package_combosaktimax',
        'zone_package_highdeno_redflag',
        'zone_package_180_30_cities',
        'zone_package_acq_combination',
        'win_java',
    ];
}
