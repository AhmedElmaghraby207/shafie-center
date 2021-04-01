<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Mail\PatientEmailVerification;
use App\Mail\PatientResetPassword;
use App\Patient;
use App\PatientDevice;
use App\PatientRecover;
use App\Transformers\PatientTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

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
            return self::errify(400, ['errors' => [__('auth.email_already_exist')]]);
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
        $patient->facebook_id = $request->facebook_id ?? null;
        $patient->google_id = $request->google_id ?? null;
        $patient->apple_id = $request->apple_id ?? null;
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
            $this->sendVerificationEmail($patient);
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

            //for set device
            "device_id" => "required",
            "firebase_token" => "required",
        ]);
        if ($validator->fails()) {
            return self::errify(400, ['validator' => $validator]);
        } else {

            $patient = Patient::where('email', '=', $request->email)
                ->where('password', md5($request->password))->where('is_active', true)->first();

            if ($patient != null) {
                if ($patient->email_verified_at == null) {
                    return self::errify(400, ['errors' => [__('auth.email_not_verified')]]);
                }
                $patient->token = md5(rand() . time());

                //set device
                $patient_device = PatientDevice::where('PatientId', $patient->id)
                    ->where('device_unique_id', $request->device_id)
                    ->first();

                if (!$patient_device) {
                    $patient_device = new PatientDevice();
                    $patient_device->created_at = date('Y-m-d H:i:s');
                }
                $patient_device->PatientId = $patient->id;
                $patient_device->device_unique_id = $request->device_id;
                $patient_device->is_logged_in = 1;
                $patient_device->token = $patient->token;
                $patient_device->firebase_token = $request->firebase_token;
                $patient_device->updated_at = date('Y-m-d H:i:s');
                $patient_device->save();
                if ($request->firebase_token) {
                    \App\Helpers\FCMHelper::Subscribe_User_To_FireBase_Topic(Config::get('constants._PATIENT_FIREBASE_TOPIC'), [$request->firebase_token]);
                }
                $patient = Fractal::item($patient)
                    ->transformWith(new PatientTransformer([
                        'id', 'first_name', 'last_name', 'is_active', 'email', 'token'
                    ]))
                    ->withResourceName('')
                    ->parseIncludes([]);

                return response()->json($patient);
            } else {
                return self::errify(400, ['errors' => [__('auth.failed_credentials')]]);
            }
        }
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
                return self::errify(400, ['errors' => [__('auth.email_not_found')]]);
            } else {
                $recover = PatientRecover::where('email', $patient->email)->first();
                if ($recover)
                    return response()->json(['error' => __('auth.reset_email_already_sent')]);

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
                $from = env('MAIL_FROM_ADDRESS');
                Mail::to($emailTo)->send(new PatientResetPassword($patient, $hash, $from));
                return response()->json(['status' => 'ok']);
            }
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "old_password" => "required",
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $patient_auth = PatientAuth::patient();
        $patient = Patient::where('email', '=', $patient_auth->email)
            ->where('password', md5($request->old_password))->first();

        if ($patient != null) {
            if ($patient->email_verified_at == null) {
                return self::errify(400, ['errors' => [__('auth.email_not_verified')]]);
            }
            $patient->password = md5($request->password);
            $patient->save();

            return response()->json(['status' => 'ok', 'message' => __('auth.password_changed_success')]);
        } else {
            return self::errify(400, ['errors' => [__('auth.invalid_old_password')]]);
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
                return self::errify(400, ['errors' => [__('auth.email_not_found')]]);
            } else if ($patient->email_verified_at) {
                return self::errify(400, ['errors' => [__('auth.email_already_verified')]]);
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
        $from = env('MAIL_FROM_ADDRESS');
        Mail::to($emailTo)->send(new PatientEmailVerification($patient, $hash, $from));
    }

    public function signupSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "mobile_os" => "required",
            "mobile_model" => "required",
            "email" => "required|email",
            "social_type" => "required|numeric|in:1,2,3",
            "social_id" => "required",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $exsitingPatient = Patient::where('email', '=', $request->email)->first();
        if ($exsitingPatient) {
            switch ($request->social_type) {
                case config('constants.SOCIAL_SIGNUP_FACEBOOK'):
                    $exsitingPatient->facebook_id = $request->social_id;
                    break;
                case config('constants.SOCIAL_SIGNUP_GOOGLE'):
                    $exsitingPatient->google_id = $request->social_id;
                    break;
                case config('constants.SOCIAL_SIGNUP_APPLE'):
                    $exsitingPatient->apple_id = $request->social_id;
                    break;
            }

            $exsitingPatient->token = md5(rand() . time());
            $exsitingPatient->email_verified_at = Carbon::now()->toDateTimeString();
            $exsitingPatient->save();
            return response()->json(['data' => $exsitingPatient]);
        }

        $newPatient = new Patient;
        if ($this->getPatientForSocialId($request->social_type, $request->social_id)) {
            return self::errify(400, ['errors' => [__('auth.social_id_already_exist')]]);
        }
        $newPatient->first_name = $request->last_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->token = md5(rand() . time());
        $newPatient->hash = md5(uniqid(rand(), true));
        $newPatient->phone = $request->phone;
        $newPatient->mobile_os = $request->mobile_os;
        $newPatient->mobile_model = $request->mobile_model;
        $newPatient->facebook_id = $request->facebook_id ?? "";
        $newPatient->google_id = $request->google_id ?? "";
        $newPatient->apple_id = $request->apple_id ?? "";
        $newPatient->email_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->phone_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->is_active = true;
        //social image url
        if ($request->image) {
            $newPatient->image = $request->image;
        }
        $saved = $newPatient->save();
        if ($saved) {
            return response()->json(['data' => $newPatient]);
        } else
            return self::errify(400, ['errors' => ['Failed']]);
    }

    public function getPatientForSocialId($social_type, $social_id)
    {
        $patient = null;
        switch ($social_type) {
            case config('constants.SOCIAL_SIGNUP_FACEBOOK'):
                $patient = Patient::where('facebook_id', $social_id)->first();
                break;
            case config('constants.SOCIAL_SIGNUP_GOOGLE'):
                $patient = Patient::where('google_id', $social_id)->first();
                break;
            case config('constants.SOCIAL_SIGNUP_APPLE'):
                $patient = Patient::where('apple_id', $social_id)->first();
                break;
            default:
                $patient = null;
                break;
        }

        if ($patient)
            return $patient->token;
        return false;
    }

    public function logout(Request $request)
    {
        $token = $request->header('x-auth-token');
        $patient_device = PatientDevice::where('token', $token)->first();

        if ($patient_device != null) {
            $patient_device->delete();
            return response()->json(['msg' => 'OK']);
        } else {
            return self::errify(400, ['errors' => [__('auth.invalid_token')]]);
        }
    }
}
