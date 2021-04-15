<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Mail\PatientEmailVerification;
use App\Mail\PatientResetPassword;
use App\Notification;
use App\Patient;
use App\PatientDevice;
use App\PatientRecover;
use App\PatientWeight;
use App\Transformers\PatientTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class AuthController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "branch_id" => "required|numeric",
            "operation_id" => "required|numeric",
            "email" => "required|email",
            "password" => "required|min:6",
            "phone" => "required",
            "gender" => "required|in:0,1",
            "birth_date" => "required|date",
            "weight" => "required|numeric",
            "height" => "required|numeric",
            "mobile_os" => "required",
            "mobile_model" => "required",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $patient = Patient::where('email', $request->email)->first();

        if ($patient != null) {
            if ($this->lang == 'ar') {
                $email_exist_msg = 'البريد الإلكترونى موجود بالفعل';
            } else {
                $email_exist_msg = 'Email already exist.';
            }
            return self::errify(400, ['errors' => [$email_exist_msg]]);
        }

        $hash = md5(uniqid(rand(), true));
        $patient = new Patient;
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->branch_id = $request->branch_id;
        $patient->operation_id = $request->operation_id;
        $patient->password = md5($request->password);
        $patient->email = $request->email;
        $patient->token = md5(rand() . time());
        $patient->hash = $hash;
        $patient->phone = $request->phone;
        $patient->birth_date = $request->birth_date;
        $patient->gender = $request->gender;
        $patient->weight = $request->weight;
        $patient->height = $request->height;
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
            $path = 'uploads/patients/patient_' . $patient->id . '/';
            $image_new_name = time() . '_' . $image->getClientOriginalName();
            $image->move($path, $image_new_name);
            $patient->image = $path . $image_new_name;
            $patient->save();
        }

        if ($weight = $request->weight) {
            $patient_weight = [
                'PatientId' => $patient->id,
                'weight' => $weight
            ];
            PatientWeight::query()->create($patient_weight);
        }

        if ($created_patient) {
            $this->sendVerificationEmail($patient);
            if ($this->lang == 'ar') {
                $saved_msg = 'تم التسجيل بنجاح, من فضلك افحص البريد الالكترونى لتفعيل البريد';
            } else {
                $saved_msg = 'You have been signed up successfully, Please check your email to confirm it.';
            }
            return response()->json(['msg' => $saved_msg]);
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
                ->where('password', md5($request->password))->first();

            if ($patient != null) {
                if ($patient->is_active == 0) {
                    if ($this->lang == 'ar') {
                        $not_active_msg = 'الحساب معطل من قبل المسئول';
                    } else {
                        $not_active_msg = 'Account is inactive from the administrator';
                    }
                    return self::errify(400, ['errors' => [$not_active_msg]]);
                }

                if ($patient->email_verified_at == null) {
                    if ($this->lang == 'ar') {
                        $email_not_verified_msg = 'البريد الإلكترونى غير مفعل';
                    } else {
                        $email_not_verified_msg = 'Email not Verified.';
                    }
                    return self::errify(400, ['errors' => [$email_not_verified_msg]]);
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
                    ->transformWith(new PatientTransformer($this->lang, [
                        'id', 'first_name', 'last_name', 'is_active', 'email', 'token', 'image'
                    ]))
                    ->withResourceName('')
                    ->parseIncludes([])->toArray();

                $unread_notifications_count = Notification::query()
                    ->where("notifiable_id", $patient['data']['id'])
                    ->where("notifiable_type", "App\Patient")
                    ->where('read_at', null)->count();
                $patient['data']['has_new_notifications'] = $unread_notifications_count > 0;

                return response()->json($patient);
            } else {
                if ($this->lang == 'ar') {
                    $invalid_credentials_msg = 'بيانات الدخول غير صحيحة';
                } else {
                    $invalid_credentials_msg = 'Please enter correct email and password!';
                }
                return self::errify(400, ['errors' => [$invalid_credentials_msg]]);
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
                if ($this->lang == 'ar') {
                    $email_not_found_msg = 'البريد غير صحيح او الحساب غير موجود';
                } else {
                    $email_not_found_msg = 'Invalid Email or account doesn\'t exist.';
                }
                return self::errify(400, ['errors' => [$email_not_found_msg]]);
            } else {
                $recover = PatientRecover::where('email', $patient->email)->first();
                if ($recover) {
                    if ($this->lang == 'ar') {
                        $email_already_sent_msg = 'تم ارسال البريد بالفعل من فضلك افحص بريدك';
                    } else {
                        $email_already_sent_msg = 'Reset email already sent, Please check your email';
                    }
                    return self::errify(400, ['errors' => [$email_already_sent_msg]]);
                }

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

                if ($this->lang == 'ar') {
                    $email_sent_msg = 'تم ارسال البريد من فضلك افحص بريدك';
                } else {
                    $email_sent_msg = 'Reset email sent, Please check your email';
                }
                return response()->json(['msg' => $email_sent_msg]);
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
                if ($this->lang == 'ar') {
                    $email_not_verified_msg = 'البريد الإلكترونى غير مفعل';
                } else {
                    $email_not_verified_msg = 'Email not Verified.';
                }
                return self::errify(400, ['errors' => [$email_not_verified_msg]]);
            }
            $patient->password = md5($request->password);
            $patient->save();

            if ($this->lang == 'ar') {
                $password_changed_msg = 'تم تغيير كلمة المرور بنجاح';
            } else {
                $password_changed_msg = 'Password changed successfully!';
            }
            return response()->json(['msg' => $password_changed_msg]);
        } else {
            if ($this->lang == 'ar') {
                $invalid_old_password_msg = 'كلمة المرور القديمة غير صحيحة';
            } else {
                $invalid_old_password_msg = 'The old password is not valid!';
            }
            return self::errify(400, ['errors' => [$invalid_old_password_msg]]);
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
                if ($this->lang == 'ar') {
                    $email_not_found_msg = 'البريد غير صحيح او الحساب غير موجود';
                } else {
                    $email_not_found_msg = 'Invalid Email or account doesn\'t exist.';
                }
                return self::errify(400, ['errors' => [$email_not_found_msg]]);
            } else if ($patient->email_verified_at) {
                if ($this->lang == 'ar') {
                    $email_already_verified_msg = 'البريد مفعل من قبل';
                } else {
                    $email_already_verified_msg = 'Email already verified.';
                }
                return self::errify(400, ['errors' => [$email_already_verified_msg]]);
            } else {
                // send Email
                $this->sendVerificationEmail($patient);
                if ($this->lang == 'ar') {
                    $email_sent_msg = 'تم ارسال البريد, من فضلك افحص بريدك';
                } else {
                    $email_sent_msg = 'Reset email sent, Please check your email';
                }
                return response()->json(['msg' => $email_sent_msg]);
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
            "last_name" => "required",
            "branch_id" => "required|numeric",
            "operation_id" => "required|numeric",
            "mobile_os" => "required",
            "mobile_model" => "required",
            "email" => "required|email",
            "gender" => "required|in:0,1",
            "birth_date" => "required|date",
            "weight" => "required|numeric",
            "height" => "required|numeric",
            "social_type" => "required|numeric|in:1,2,3",
            "social_id" => "required",
            "device_id" => "required",
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

            //set device
            $patient_device = PatientDevice::where('PatientId', $exsitingPatient->id)
                ->where('device_unique_id', $request->device_id)
                ->first();

            if (!$patient_device) {
                $patient_device = new PatientDevice();
                $patient_device->created_at = date('Y-m-d H:i:s');
            }
            $patient_device->PatientId = $exsitingPatient->id;
            $patient_device->device_unique_id = $request->device_id;
            $patient_device->is_logged_in = 1;
            $patient_device->token = $exsitingPatient->token;
            $patient_device->firebase_token = $request->firebase_token;
            $patient_device->updated_at = date('Y-m-d H:i:s');
            $patient_device->save();
            if ($request->firebase_token) {
                \App\Helpers\FCMHelper::Subscribe_User_To_FireBase_Topic(Config::get('constants._PATIENT_FIREBASE_TOPIC'), [$request->firebase_token]);
            }

            $patient = Fractal::item($exsitingPatient)
                ->transformWith(new PatientTransformer($this->lang, [
                    'id', 'first_name', 'last_name', 'is_active', 'email', 'token', 'image'
                ]))
                ->withResourceName('')
                ->parseIncludes([])->toArray();

            $unread_notifications_count = Notification::query()
                ->where("notifiable_id", $patient['data']['id'])
                ->where("notifiable_type", "App\Patient")
                ->where('read_at', null)->count();
            $patient['data']['has_new_notifications'] = $unread_notifications_count > 0;

            return response()->json($patient);
        }

        $newPatient = new Patient;
        if ($this->getPatientForSocialId($request->social_type, $request->social_id)) {
            if ($this->lang == 'ar') {
                $social_id_exist_msg = 'رقم حساب التواصل الاجتماعى موجود بالفعل';
            } else {
                $social_id_exist_msg = 'Social id is already taken';
            }
            return self::errify(400, ['errors' => [$social_id_exist_msg]]);
        }
        $newPatient->first_name = $request->last_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->branch_id = $request->branch_id;
        $newPatient->operation_id = $request->operation_id;
        $newPatient->email = $request->email;
        $newPatient->token = md5(rand() . time());
        $newPatient->hash = md5(uniqid(rand(), true));
        $newPatient->phone = $request->phone;
        $newPatient->birth_date = $request->birth_date;
        $newPatient->gender = $request->gender;
        $newPatient->weight = $request->weight;
        $newPatient->height = $request->height;
        $newPatient->mobile_os = $request->mobile_os;
        $newPatient->mobile_model = $request->mobile_model;
        $newPatient->facebook_id = $request->facebook_id ?? "";
        $newPatient->google_id = $request->google_id ?? "";
        $newPatient->apple_id = $request->apple_id ?? "";
        $newPatient->email_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->phone_verified_at = Carbon::now()->toDateTimeString();
        $newPatient->is_active = true;
        switch ($request->social_type) {
            case config('constants.SOCIAL_SIGNUP_FACEBOOK'):
                $newPatient->facebook_id = $request->social_id;
                break;
            case config('constants.SOCIAL_SIGNUP_GOOGLE'):
                $newPatient->google_id = $request->social_id;
                break;
            case config('constants.SOCIAL_SIGNUP_APPLE'):
                $newPatient->apple_id = $request->social_id;
                break;
        }
        //social image url
        if ($request->image) {
            $newPatient->image = $request->image;
        }
        $newPatient->save();

        if ($newPatient) {
            if ($weight = $request->weight) {
                $patient_weight = [
                    'PatientId' => $newPatient->id,
                    'weight' => $weight
                ];
                PatientWeight::query()->create($patient_weight);
            }

            //set device
            $patient_device = PatientDevice::where('PatientId', $newPatient->id)
                ->where('device_unique_id', $request->device_id)
                ->first();

            if (!$patient_device) {
                $patient_device = new PatientDevice();
                $patient_device->created_at = date('Y-m-d H:i:s');
            }
            $patient_device->PatientId = $newPatient->id;
            $patient_device->device_unique_id = $request->device_id;
            $patient_device->is_logged_in = 1;
            $patient_device->token = $newPatient->token;
            $patient_device->firebase_token = $request->firebase_token;
            $patient_device->updated_at = date('Y-m-d H:i:s');
            $patient_device->save();
            if ($request->firebase_token) {
                \App\Helpers\FCMHelper::Subscribe_User_To_FireBase_Topic(Config::get('constants._PATIENT_FIREBASE_TOPIC'), [$request->firebase_token]);
            }

            $patient = Fractal::item($newPatient)
                ->transformWith(new PatientTransformer($this->lang, [
                    'id', 'first_name', 'last_name', 'is_active', 'email', 'token', 'image'
                ]))
                ->withResourceName('')
                ->parseIncludes([])->toArray();

            $unread_notifications_count = Notification::query()
                ->where("notifiable_id", $patient['data']['id'])
                ->where("notifiable_type", "App\Patient")
                ->where('read_at', null)->count();
            $patient['data']['has_new_notifications'] = $unread_notifications_count > 0;

            return response()->json($patient);
        } else
            return self::errify(400, ['errors' => ['Failed']]);
    }

    public function getPatientForSocialId($social_type, $social_id)
    {
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
            if ($this->lang == 'ar') {
                $signed_out_msg = 'تم تسجيل الخروج بنجاح';
            } else {
                $signed_out_msg = 'You have been signed out successfully';
            }
            return response()->json(['msg' => $signed_out_msg]);
        } else {
            if ($this->lang == 'ar') {
                $invalid_token_msg = 'الرمز منتهى او غير صحيح';
            } else {
                $invalid_token_msg = 'Invalid or expired Token';
            }
            return self::errify(400, ['errors' => [$invalid_token_msg]]);
        }
    }
}
