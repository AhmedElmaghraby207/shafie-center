<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Patient;
use App\PatientDevice;
use App\PatientRecover;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Snowfire\Beautymail\Beautymail;

class AuthController extends PatientApiController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:patients",
            "password" => "required|min:6",
            "phone" => "required",
            "mobile_os" => "required",
            "mobile_model" => "required",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);
        $patient = Patient::where('email', $request->email)->first();
        if ($patient != null) {
            return self::errify(400, ['errors' => ['patient.email_already_exist']]);
        }

        $hash = md5(uniqid(rand(), true));
        $patient = new Patient;
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->password = md5($request->password);
        $patient->email = $request->email;
        $patient->token = md5(rand() . time());
        $patient->hash = $hash;
        $patient->phone = $request->phone;
        $patient->mobile_os = $request->mobile_os;
        $patient->mobile_model = $request->mobile_model;
        $patient->facebook_id = $request->facebook_id ?? "";
        $patient->google_id = $request->google_id ?? "";
        $patient->apple_id = $request->apple_id ?? "";
        $patient->email_verified_at = null;
        $patient->phone_verified_at = null;
        $patient->is_active = true;

        $created_patient = $patient->save();

        if ($image = $request->image) {
            $path = 'uploads/patients/patient_' . $created_patient->id . '/';
            $image_new_name = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $image_new_name);
            $created_patient->image = $path . $image_new_name;
            $created_patient->save();
        }

        if ($created_patient) {
//            $this->sendVerificationEmail($patient);
            return response()->json(['token' => $patient->token]);
        } else {
            return self::errify(400, ['errors' => ['Failed']]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "password" => "required",
            "email" => "required",
        ]);
        if ($validator->fails()) {
            return self::errify(400, ['validator' => $validator]);
        } else {

            $patient = Patient::where('email', '=', $request->email)
                ->where('password', md5($request->password))->where('is_active', true)->first();

            if ($patient != null) {
                if ($patient->email_verified_at == null) {
                    return self::errify(400, ['errors' => ['auth.email_not_verified']]);
                }
                $patient->token = md5(rand() . time());
                return response()->json(['data' => $patient]);
            } else {
                return self::errify(400, ['errors' => ['Please enter correct email and password']]);
            }
        }
    }

    public function setDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "device_id" => "required",
            "status" => "required",
            "firebase_token" => "required",
        ]);
        if ($validator->fails()) {
            return self::errify(400, ['validator' => $validator]);
        }
        $token = $request->header('x-auth-token');
        $patient = Patient::where('email', '=', $request->email)->first();
        if ($patient == null) {
            return self::errify(400, ['errors' => ['Failed to create token']]);
        }

        $patient_device = PatientDevice::where('PatientId', $patient->id)
            ->where('device_unique_id', $request->device_id)
            ->first();

        if (!$patient_device) {
            $patient_device = new PatientDevice();
            $patient_device->date_created = date('Y-m-d H:i:s');
        }
        $patient_device->PatientId = $patient->id;
        $patient_device->device_unique_id = $request->device_id;
        ($request->status == 1) ? $patient_device->is_logged_in = 1 : $patient_device->is_logged_in = 0;
        $patient_device->token = $token;
        $patient_device->firebase_token = $request->firebase_token;
        $patient_device->updated_at = date('Y-m-d H:i:s');
        $patient_device->save();
        if ($request->firebase_token) {
            \App\Helpers\FCMHelper::Subscribe_User_To_FireBase_Topic(Config::get('constants._PATIENTS_FIREBASE_TOPIC'), [$request->firebase_token]);
        }
        return response()->json(['data' => $patient_device]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), ["email" => "required|email"]);
        if ($validator->fails()) {
            return self::errify(400, ['validator' => $validator]);
        } else {
            $email = $request["email"];
            $patient = Patient::where('email', '=', $email)->first();

            if ($patient == null) {
                return self::errify(400, ['errors' => ['auth.email_not_found']]);
            } else {
                $hash = md5(uniqid(rand(), true));
                $patientRecover = new PatientRecover();
                $patientRecover->email = $patient->email;
                $patientRecover->hash = $hash;
                $patientRecover->password = '';
                $patientRecover->save();
                // send Email
                global $emailTo;
                $emailTo = $patient->email;
                global $emailToName;
                $emailToName = $patient->first_name . ' ' . $patient->last_name;
                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.patient.resetpassword', [
                    "token" => $hash,
                    "patient" => $patient], function ($message) {
                    global $emailTo;
                    global $emailToName;
                    $message
                        ->from(env('MAIL_FROM'))
                        ->to($emailTo, $emailToName)
                        ->subject(trans('patient.mail_reset_password_subject'));
                });
                return response()->json(['status' => 'ok']);
            }
        }
    }

    public function resendEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), ["email" => "required|email"]);
        if ($validator->fails()) {
            return self::errify(400, ['validator' => $validator]);
        } else {
            $email = $request["email"];
            $patient = Patient::where('email', '=', $email)->first();
            if ($patient == null) {
                return self::errify(400, ['errors' => ['auth.email_not_found']]);
            } else if ($patient->email_verified_at) {
                return self::errify(400, ['errors' => ['auth.email_already_verified']]);
            } else {
                // send Email
                $this->sendVerificationEmail($patient);
                return response()->json(['status' => 'ok']);
            }
        }
    }

    private function sendVerificationEmail($patient)
    {
        global $emailTo;
        $emailTo = $patient->email;
        global $emailToName;
        $emailToName = $patient->first_name . ' ' . $patient->last_name;
        $hash = $patient->hash;
        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('emails.patient.register', [
            "token" => $hash,
            "patient" => $patient], function ($message) {
            global $emailTo;
            global $emailToName;
            $message
                ->from(env('MAIL_FROM'))
                ->to($emailTo, $emailToName)
                ->subject(trans('patient.mail_confirm_email_subject'));
        });
    }

    public function checkEmailISAlreadyTaken($email)
    {
        $patient = Patient::where('email', '=', $email)->first();
        if ($patient)
            return $patient->token;
        return false;
    }

    public function signupWithFacebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "facebook_id" => "required",
            "mobile_os" => "required",
            "mobile_model" => "required",
            "email" => "required|email",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $exsitingPatient = Patient::where('email', '=', $request->email)->first();
        if ($exsitingPatient) {
            $exsitingPatient->facebook_id = $request->facebook_id ?? "";
            $exsitingPatient->token = md5(rand() . time());
            $exsitingPatient->email_verified_at = Carbon::now()->toDateTimeString();
            $exsitingPatient->save();
            return response()->json(['data' => $exsitingPatient]);
        }

        $newPatient = new Patient;
        if ($this->getTokenForFaceBookTokenFound($request->facebook_id)) {
            return self::errify(400, ['errors' => ['facebook_id is already taken']]);
        }
        $newPatient->first_name = $request->last_name;
        $newPatient->flast_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->token = md5(rand() . time());
        $newPatient->hash = md5(uniqid(rand(), true));
        $newPatient->mobile = $request->mobile;
        $newPatient->mobile_os = $request->mobile_os;
        $newPatient->mobile_model = $request->mobile_model;
        $newPatient->facebook_id = $request->facebook_id ?? "";
        $newPatient->google_id = $request->google_id ?? "";
        $newPatient->apple_id = $request->apple_id ?? "";
        $newPatient->email_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->phone_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->is_active = true;
        //facebook image url
        if ($request->image) {
            $newPatient->image = $request->image;
        }
        $saved = $newPatient->save();
        if ($saved) {
            return response()->json(['data' => $newPatient]);
        } else
            return self::errify(400, ['errors' => ['Failed']]);
    }

    public function getTokenForFaceBookTokenFound($facebook_id)
    {
        $patient = Patient::where('facebook_id', $facebook_id)->first();
        if ($patient)
            return $patient->token;
        return false;
    }

    public function checkFaceBook_idFound($facebook_id)
    {
        $facebook_id_count = Patient::where('facebook_id', $facebook_id)->count();
        if ($facebook_id_count > 0) {
            return $facebook_id_count;
        } else {
            return 0;
        }
    }

    public function signupWithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "google_id" => "required",
            "mobile_os" => "required",
            "mobile_model" => "required",
            "email" => "required|email",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $exsitingPatient = Patient::where('email', '=', $request->email)->first();
        if ($exsitingPatient) {
            $exsitingPatient->google_id = $request->google_id ?? "";
            $exsitingPatient->token = md5(rand() . time());
            $exsitingPatient->email_verified_at = Carbon::now()->toDateTimeString();
            $exsitingPatient->save();
            return response()->json(['data' => $exsitingPatient]);
        }

        $newPatient = new Patient;
        if ($this->getTokenForGoogleTokenFound($request->google_id)) {
            return self::errify(400, ['errors' => ['google_id is already taken']]);
        }
        $newPatient->first_name = $request->last_name;
        $newPatient->flast_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->token = md5(rand() . time());
        $newPatient->hash = md5(uniqid(rand(), true));
        $newPatient->mobile = $request->mobile;
        $newPatient->mobile_os = $request->mobile_os;
        $newPatient->mobile_model = $request->mobile_model;
        $newPatient->facebook_id = $request->facebook_id ?? "";
        $newPatient->google_id = $request->google_id ?? "";
        $newPatient->apple_id = $request->apple_id ?? "";
        $newPatient->email_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->phone_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->is_active = true;
        //google image url
        if ($request->image) {
            $newPatient->image = $request->image;
        }
        $saved = $newPatient->save();
        if ($saved) {
            return response()->json(['data' => $newPatient]);
        } else
            return self::errify(400, ['errors' => ['Failed']]);
    }

    public function getTokenForGoogleTokenFound($google_id)
    {
        $patient = Patient::where('google_id', $google_id)->first();
        if ($patient)
            return $patient->token;
        return false;
    }

    public function signupWithApple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "apple_id" => "required",
            "mobile_os" => "required",
            "mobile_model" => "required",
            "email" => "required|email",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $exsitingPatient = Patient::where('email', '=', $request->email)->first();
        if ($exsitingPatient) {
            $exsitingPatient->apple_id = $request->apple_id ?? "";
            $exsitingPatient->token = md5(rand() . time());
            $exsitingPatient->email_verified_at = Carbon::now()->toDateTimeString();
            $exsitingPatient->save();
            return response()->json(['data' => $exsitingPatient]);
        }

        $newPatient = new Patient;
        if ($this->getTokenForAppleTokenFound($request->apple_id)) {
            return self::errify(400, ['errors' => ['apple_id is already taken']]);
        }
        $newPatient->first_name = $request->last_name;
        $newPatient->flast_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->token = md5(rand() . time());
        $newPatient->hash = md5(uniqid(rand(), true));
        $newPatient->mobile = $request->mobile;
        $newPatient->mobile_os = $request->mobile_os;
        $newPatient->mobile_model = $request->mobile_model;
        $newPatient->facebook_id = $request->facebook_id ?? "";
        $newPatient->google_id = $request->google_id ?? "";
        $newPatient->apple_id = $request->apple_id ?? "";
        $newPatient->email_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->phone_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->is_active = true;
        //apple image url
        if ($request->image) {
            $newPatient->image = $request->image;
        }
        $saved = $newPatient->save();
        if ($saved) {
            return response()->json(['data' => $newPatient]);
        } else
            return self::errify(400, ['errors' => ['Failed']]);
    }

    public function getTokenForAppleTokenFound($apple_id)
    {
        $patient = Patient::where('apple_id', $apple_id)->first();
        if ($patient)
            return $patient->token;
        return false;
    }

}
