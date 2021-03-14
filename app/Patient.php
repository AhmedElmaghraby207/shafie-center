<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'phone_verified_at',
        'remember_token',
        'is_active',
        'image',
        'age',
        'weight',
        'height',
        'gender',
        'address',
        'facebook_id',
        'google_id',
        'apple_id',
        'mobile_os',
        'mobile_model'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
