<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimTeritorialNational extends Model
{
    use HasFactory;

    protected $table = 'dim_teritorial_national';

    protected $primaryKey = ['city', 'sub_district', 'village'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id_area',
        'area',
        'regional',
        'branch',
        'sub_branch',
        'cluster',
        'city',
        'city_2',
        'sub_district',
        'village',
        'province',
    ];

    /**
     * Set the keys for a save update query.
     * Laravel doesn't support composite primary keys natively,
     * so we need to override this method.
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
