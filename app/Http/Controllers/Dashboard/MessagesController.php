<?php

namespace App\Http\Controllers\Dashboard;

use App\Message;
use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class MessagesController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:message-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:message-show', ['only' => ['show']]);
        $this->middleware('permission:message-read', ['only' => ['read']]);
        $this->middleware('permission:message-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $patients = Patient::all();
        return view('dashboard.messages.index')->with('patients', $patients);
    }

    public function list(Request $request)
    {
        $message = $request->message;
        $patient_id = $request->patient_id;
        $messages = Message::with('patient')->where('id', '!=', '-1');
        if ($message) {
            $messages = $messages->where('message', 'like', '%' . $message . '%');
        }
        if ($patient_id) {
            $messages = $messages->where('PatientId', '=', $patient_id);
        }
        return datatables()->of($messages)->toJson();
    }

    public function read($id, Request $request)
    {
        $message = Message::find($id);
        $message->read_at = Carbon::now()->toDateTimeString();
        $read_message = $message->save();

        if ($read_message) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.updated_alert_message', ['attribute' => Lang::get('message.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('message.attribute_name')]));
            return redirect()->back();
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'fail',
                    'error_message' => 'Something went wrong'
                ]);
            }
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function destroy($id, Request $request)
    {
        $message = Message::find($id);
        $deleted_message = $message->delete();

        if ($deleted_message) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('message.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('message.attribute_name')]));
            return redirect()->back();
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'fail',
                    'error_message' => 'Something went wrong'
                ]);
            }
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back();
        }

    }
}
