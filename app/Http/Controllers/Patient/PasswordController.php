<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Patient;
use App\PatientRecover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Snowfire\Beautymail\Beautymail;

class PasswordController extends Controller
{

    public function forgot_password()
    {
        return view('patient/auth/password', []);
    }

    public function postforgot_password(Request $request)
    {
        $this->validate($request, ['email' => 'required']);
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

            try {
                // send Email
                global $emailTo;
                $emailTo = $patient->email;
                global $emailToName;
                $emailToName = $patient->name;

                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.patient.resetPassword', [
                    "token" => $hash,
                    "patient" => $patient], function ($message) {
                    global $emailTo;
                    global $emailToName;
                    $message
                        ->from(env('MAIL_FROM'))
                        ->to($emailTo, $emailToName)
                        ->subject(trans('patient.mail_reset_password_subject'));
                });
            } catch (Exception $e) {
            }

            return redirect()->back()->with('success', "Reset Email successfully sent.");
        }
    }

    public function reset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }
        return view('patient/auth/reset', [])->with('token', $token);
    }

    public function post_reset(Request $request)
    {
        $v = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withInput($request->only('email'))
                ->withErrors($v->errors());
        }

        $password = $request["password"];
        $hash = $request["token"];

        $patientRecover = PatientRecover::where('hash', '=', $hash)->first();
        if ($hash == null || $patientRecover == null) {
            return redirect()->back()->with('error', "Invalid or expired Token");
        } else {
            $patientRecover->delete();
            $email = $patientRecover->email;
            $patient = Patient::where('email', '=', $email)->first();
            $patient->password = md5($password);
            $patient->save();

            return redirect()->back()->with('success', "Password successfully changed.");
        }
    }

}
