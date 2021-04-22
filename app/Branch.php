<?php

namespace App;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use SpatialTrait;

    protected $guarded = ['id'];

    protected $appends = ['lat', 'lng'];

    protected $spatialFields = [
        'location'
    ];

    public function getLatAttribute()
    {
        if ($this->location) {
            return $this->location->getLat();
        } else {
            return null;
        }
    }

    public function getLngAttribute()
    {
        if ($this->location) {
            return $this->location->getLng();
        } else {
            return null;
        }
    }

    public function getImageAttribute($value): string
    {
        if ($value) {
            return asset($value);
        }
        return asset("/uploads/defaults/location.png");
    }
}
