<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
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
            return redirect()->back()->withErrors(['errors', 'Please enter correct email and password']);
        if ($user_admin->email_verified_at == '0000-00-00 00:00:00' || $user_admin->email_verified_at == null)
            return redirect()->back()->withErrors(['errors', 'Email Entered is not verified!']);
        if ($request->remember_me) {
            $user_admin->remember_token = md5(rand() . time());
            $is_token_generated = $user_admin->save();
            if (!$is_token_generated)
                return redirect()->back()->withErrors(['errors', 'Database Error!']);
        }
        $request->session()->put('user_admin', $user_admin);
        if ($request->return_url)
            return redirect()->to($request->return_url);
        return redirect()->to('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->to('/login');
    }
}
