<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessagesController extends PatientApiController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "message" => "required"
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $patient_auth = PatientAuth::patient();

        $message = new Message();
        $message->PatientId = $patient_auth->id;
        $message->message = $request->message;
        $message->save();

        return response()->json(['msg' => 'saved']);

    }
}
