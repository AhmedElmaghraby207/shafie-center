<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'patients_ids',
        'subject',
        'message',
        'publish_at',
        'created_at'
    ];
}
