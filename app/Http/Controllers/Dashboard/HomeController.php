<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Faq;
use App\Message;
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
        $patients_count = Patient::all()->count();
        $branches_count = Branch::all()->count();
        $messages_count = Message::all()->count();
        $faqs_count = Faq::all()->count();

        $latest_patients = Patient::query()->orderBy('created_at', 'desc')->take(5)->get();
        return view('dashboard.dashboard')->with([
            'patients_count' => $patients_count,
            'branches_count' => $branches_count,
            'messages_count' => $messages_count,
            'faqs_count' => $faqs_count,

            'latest_patients' => $latest_patients,
        ]);
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
