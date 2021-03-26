<?php

namespace App\Http\Controllers\Dashboard;

use App\Patient;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function dashboard(Request $request)
    {
        return view('dashboard.dashboard')->with([]);
    }

    public function emailTempView(Request $request)
    {
        $p = Patient::all()->first();
        return view('emails.patient.reset-password')
            ->with([
                'patient' => $p,
                'token' => $p->token,
                'senderName' => '',
                'logo' => ['width' => 70, 'height' => 70],
            ]);
    }

}
