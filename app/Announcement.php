<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'patients_ids',
        'subject',
        'message',
        'publish_at',
        'created_at'
    ];
}
