<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App;
use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Helpers\CommonHelper;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends PatientApiController
{
    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function list(Request $request)
    {
        $patient = PatientAuth::patient();

        $res = [];
        $page = $request->page ?: 0; /* Actual page */
        $limit = 10; /* Limit per page */

        $notifications = Notification::query()->where("notifiable_id", $patient->id)
            ->where("notifiable_type", "App\Patient")->latest()->paginate($limit);

        foreach ($notifications as $notification) {
            $h = App::make($notification->type);
            $notification->data = json_decode($notification->data, JSON_FORCE_OBJECT);
            $subject = $h->toSubject($notification->data);
            $content = $h->toString($notification->data);
            $is_read = ($notification->read_at != null);
            $data = $notification->data;
            $created_at = Carbon::parse($notification->created_at);
            $date = $created_at->toFormattedDateString();
            $h->object["subject"] = $subject;
            $h->object["content"] = $content;
            $h->object["date"] = $date;
            $h->object["is_read"] = $is_read;
            $obj = $h->toObject($notification->data);
            $obj['id'] = $notification->id;
            $res[] = $obj;
        }
        $data = CommonHelper::customPaginationByTotal($res, $notifications->perPage(), $notifications->total());
        return response()->json(['data' => $data]);
    }

    public function markRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required"
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $patient = PatientAuth::patient();

        $n = Notification::query()
            ->where("id", '=', $request->id)
            ->where("notifiable_id", $patient->id)
            ->where("notifiable_type", "App\Patient")->first();

        $n->read_at = Carbon::now('UTC');
        $n->save();

        return response()->json(['msg' => 'Saved']);
    }
}
