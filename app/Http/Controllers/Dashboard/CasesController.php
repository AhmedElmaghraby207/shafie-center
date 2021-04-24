<?php

namespace App\Http\Controllers\Dashboard;

use App\PatientCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class CasesController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:case-list', ['only' => ['index', 'list', 'show']]);
        $this->middleware('permission:case-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:case-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:case-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('dashboard.cases.index');
    }

    public function list(Request $request)
    {
        $case_name = $request->input('case_name');
        $cases = PatientCase::query();
        if ($case_name) {
            $cases = $cases->where('case_name', 'like', '%' . $case_name . '%');
        }
        return datatables()->of($cases)->toJson();
    }

    public function create()
    {
        return view('dashboard.cases.create');
    }

    public function store(Request $request)
    {
        $validator_array = [
            'image_before' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            'image_after' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096'
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $created_case = new PatientCase();

        if ($image_before = $request->image_before) {
            if ($created_case->image_before) {
                @unlink($created_case->getOriginal('image_before'));
            }
            $path = 'uploads/cases/';
            $image_new_name = time() . '_' . $image_before->getClientOriginalName();
            $image_before->move($path, $image_new_name);
            $created_case->image_before = $path . $image_new_name;
        }

        if ($image_after = $request->image_after) {
            if ($created_case->image_after) {
                @unlink($created_case->getOriginal('image_after'));
            }
            $path = 'uploads/cases/';
            $image_new_name = time() . '_' . $image_after->getClientOriginalName();
            $image_after->move($path, $image_new_name);
            $created_case->image_after = $path . $image_new_name;
        }
        $created_case->case_name = $request->input('case_name');
        $created_case->description = $request->input('description');
        $created_case->save();

        session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('case.attribute_name')]));
        return redirect()->route('case.index');
    }

    public function edit($id)
    {
        $case = PatientCase::find($id);
        return view('dashboard.cases.edit')->with(['case' => $case]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'image_before' => 'mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            'image_after' => 'mimes:jpg,jpeg,png,bmp,tiff|max:4096'
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $updated_case = PatientCase::query()->find($id);

        if ($updated_case) {

            if ($image_before = $request->image_before) {
                if ($updated_case->image_before) {
                    @unlink($updated_case->getOriginal('image_before'));
                }
                $path = 'uploads/cases/';
                $image_new_name = time() . '_' . $image_before->getClientOriginalName();
                $image_before->move($path, $image_new_name);
                $updated_case->image_before = $path . $image_new_name;
            }
            if ($image_after = $request->image_after) {
                if ($updated_case->image_after) {
                    @unlink($updated_case->getOriginal('image_after'));
                }
                $path = 'uploads/cases/';
                $image_new_name = time() . '_' . $image_after->getClientOriginalName();
                $image_after->move($path, $image_new_name);
                $updated_case->image_after = $path . $image_new_name;
            }
            $updated_case->case_name = $request->input('case_name');
            $updated_case->description = $request->input('description');
            $updated_case->save();

            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('case.attribute_name')]));
            return redirect()->route('case.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function destroy($id, Request $request)
    {
        $case = PatientCase::find($id);
        $deleted_case = $case->delete();

        if ($deleted_case) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('case.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('case.attribute_name')]));
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
