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

    public function firebase_tokens()
    {
        $tokens = Patient::where('email', $this->email)
            ->join('patient_devices', 'patients.id', '=', 'patient_devices.PatientId')
            ->where('is_logged_in', '1')
            ->get()
            ->pluck('firebase_token')
            ->toArray();

        return $tokens;
    }

    public function get_new_notifications_count()
    {
        $notifications_query = Notification::where("notifiable_id", $this->id)
            ->where("notifiable_type", "App\Patient")
            ->whereRaw(DB::raw("TIMESTAMP(`created_at`) >  TIMESTAMP('" . Carbon::parse($this->viewed_notifications_at) . "')"))
            ->whereNull('read_at')->get();
        return $notifications_query->count();
    }
}
