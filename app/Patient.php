<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Patient extends Model
{
    use Notifiable, HasRoles;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImageAttribute($value): string
    {
        if ($value) {
            return asset($value);
        }
        return asset("/uploads/defaults/admin.png");
    }

    public function firebase_tokens()
    {
        return Patient::where('email', $this->email)
            ->join('patient_devices', 'patients.id', '=', 'patient_devices.PatientId')
            ->where('is_logged_in', '1')
            ->get()
            ->pluck('firebase_token')
            ->toArray();
    }

    public function get_new_notifications_count()
    {
        $notifications_query = Notification::where("notifiable_id", $this->id)
            ->where("notifiable_type", "App\Patient")
            ->whereRaw(DB::raw("TIMESTAMP(`created_at`) >  TIMESTAMP('" . Carbon::parse($this->viewed_notifications_at) . "')"))
            ->whereNull('read_at')->get();
        return $notifications_query->count();
    }

    public function weights(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\PatientWeight', 'PatientId');
    }
}
