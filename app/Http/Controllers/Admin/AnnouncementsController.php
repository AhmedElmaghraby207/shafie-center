<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use App\Mail\PatientAnnouncement;
use App\Notifications\P_Announcement;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AnnouncementsController extends BaseController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function create(Request $request)
    {
        return view('admin.announcements.create');
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "subject" => "required|min:3|max:500",
            "message" => "required|min:3|max:2000",
        ]);
        if ($validator->fails())
            return redirect()->to(route('announcement.create'))->withErrors($validator);

        if (empty($request->mail_checkbox) && empty($request->notify_checkbox))
            return redirect()->back()->with('error_message', 'You must select Method to send (Mail Or Notification)!');

        $announcement = new Announcement;
        $announcement->subject = $request->subject;
        $announcement->message = $request->message;

        if ($request->patients) {
            $patients_ids = '';
            $patients_ids_for_tokens = array();
            $array_ids = explode(',', $request->patients);
            foreach ($array_ids as $key => $id) {
                $patients_ids .= '[' . $id . ']';
                $patients_ids_for_tokens[] = (int)$id;
            }
            $announcement->patients_ids = $patients_ids;
        } else
            $announcement->patients_ids = -1;

        $announcement->publish_at = date('Y-m-d H:i:s');
        $announcement->created_at = date('Y-m-d H:i:s');
        $announcement->save();
        if ($request->mail_checkbox) {
            if ($request->patients)
                $array_patients = Patient::whereIn('id', $patients_ids_for_tokens)->get();
            else
                $array_patients = Patient::get();

            foreach ($array_patients as $key => $patient) {
                try {
                    if ($patient->is_email_verified) {
                        $validator = validator::make(['email' => $patient->email], [
                            "email" => "email:rfc"
                        ]);
                        if (!$validator->fails()) {
                            // send Email
                            global $emailTo;
                            $emailTo = $patient->email;
                            global $emailToName;
                            $emailToName = $patient->first_name . ' ' . $patient->last_name;
                            $hash = $patient->hash;
                            $from = env('MAIL_FROM_ADDRESS');
                            Mail::to($emailTo)->send(new PatientAnnouncement($patient, $hash, $from));
                        }
                    }
                } catch (\Exception $e) {
                    // Get error here
                }
            }
        }

        if ($request->notify_checkbox) {
            if ($request->patients_checkbox) {
                if ($request->patients) {
                    foreach ($patients_ids_for_tokens as $patient_id) {
                        $patient = Patient::find($patient_id);
                        $patient->notify(new P_Announcement($request->subject, $request->message));
                    }
                }
            }
        }

        return redirect()->back()->with('success_message', 'Successfully Sent!');
    }

}
