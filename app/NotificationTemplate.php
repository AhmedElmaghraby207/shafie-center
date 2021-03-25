<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = ['display_type', 'name', 'subject', 'template', 'is_popup', 'popup_image'];
}
