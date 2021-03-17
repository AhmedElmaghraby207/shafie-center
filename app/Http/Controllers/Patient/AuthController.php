<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $patientModel;

    public function __construct(Patient $patientModel)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->patientModel = $patientModel;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function verify_email(Request $request, $token)
    {
        $hash = $token;
        $patient = Patient::where('hash', '=', $hash)
            ->first();
        if ($hash == null || $patient == null) {
            return view('patient/auth/verify-email')->with('error', "Invalid Token");
        } else {
            $patient->email_verified_at = Carbon::now()->toDateTimeString();
            $patient->save();
            return view('patient/auth/verify-email')->with('success', "Email Verified Successfully.");
        }
    }

}
