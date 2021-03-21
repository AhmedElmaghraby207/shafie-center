<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\AdminRecover;
use App\Mail\AdminResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends AdminController
{
    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function home(Request $request)
    {
        return view('admin.dashboard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required",
        ]);
        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors());
        $user_admin = Admin::where('email', $request->email)->where('password', md5($request->password))->first();
        if (!$user_admin)
            return redirect()->back()->withErrors(['Please enter correct email and password']);
        if ($user_admin->email_verified_at == '0000-00-00 00:00:00' || $user_admin->email_verified_at == null)
            return redirect()->back()->withErrors(['Email Entered is not verified!']);
        if ($request->remember_me) {
            $user_admin->remember_token = md5(rand() . time());
            $is_token_generated = $user_admin->save();
            if (!$is_token_generated)
                return redirect()->back()->withErrors(['Database Error!']);
        }
        $request->session()->put('user_admin', $user_admin);
        if ($request->return_url)
            return redirect()->to($request->return_url);
        return redirect()->to('dashboard');
    }

    public function getForgotPassword()
    {
        return view('admin.forgot-password');
    }

    public function postForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), ["email" => "required|email"]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $email = $request["email"];
            $admin = Admin::where('email', '=', $email)->first();

            if ($admin == null) {
                return redirect()->back()->withErrors(['Please enter correct email']);
            } else {
                $recover = AdminRecover::where('email', $admin->email)->first();
                if ($recover)
                    return redirect()->back()->withErrors(['Reset email already sent, Please check your email']);

                $hash = md5(uniqid(rand(), true));
                $adminRecover = new AdminRecover();
                $adminRecover->email = $admin->email;
                $adminRecover->hash = $hash;
                $adminRecover->password = '';
                $adminRecover->save();
                // send Email
                global $emailTo;
                $emailTo = $admin->email;
                global $emailToName;
                $emailToName = $admin->name;
                $from = env('MAIL_FROM_ADDRESS');
                Mail::to($emailTo)->send(new AdminResetPassword($admin, $hash, $from));
                return view('partials/success')->with(['message' => "Reset email has been sent successfully!"]);
                return response()->json(['status' => 'ok']);
            }
        }
    }

    public function getResetPassword($token)
    {
        $adminRecover = AdminRecover::where('hash', '=', $token)->first();
        if ($token == null || $adminRecover == null) {
            return view('partials/expired-token')->with('error', "Invalid or expired Token");
        }

        return view('admin/reset-password')->with('token', $token);
    }

    public function postResetPassword(Request $request)
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

        $adminRecover = AdminRecover::where('hash', '=', $hash)->first();
        if ($hash == null || $adminRecover == null) {
            return redirect()->back()->with('error', "Invalid or expired Token");
        } else {
            $adminRecover->delete();
            $email = $adminRecover->email;
            $admin = Admin::where('email', '=', $email)->first();
            $admin->password = md5($password);
            $admin->save();

            return redirect()->route('auth.login')->with(['status' => 'success', "message" => "Password changed successfully!"]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->to('/login');
    }
}
