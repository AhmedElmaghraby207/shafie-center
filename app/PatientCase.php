<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientCase extends Model
{
    protected $guarded = ['id'];

    public function getImageBeforeAttribute($value): string
    {
        if ($value) {
            return asset($value);
        }
        return asset("/uploads/defaults/admin.png");
    }

    public function getImageAfterAttribute($value): string
    {
        if ($value) {
            return asset($value);
        }
        return asset("/uploads/defaults/admin.png");
    }
}
