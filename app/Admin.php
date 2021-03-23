<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = ['name', 'email', 'password', 'image'];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return asset("/uploads/defaults/admin.png");
    }
}
