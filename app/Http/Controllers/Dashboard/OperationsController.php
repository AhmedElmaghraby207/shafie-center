<?php

namespace App\Http\Controllers\Dashboard;

use App\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;

class OperationsController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:operation-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:operation-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:operation-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:operation-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('dashboard.operations.index');
    }

    public function list(Request $request)
    {
        $name_en = $request->input('name_en');
        $name_ar = $request->input('name_ar');

        $operations = Operation::query();
        if ($name_en) {
            $operations = $operations->where('name_en', 'like', '%' . $name_en . '%');
        }
        if ($name_ar) {
            $operations = $operations->where('name_ar', 'like', '%' . $name_ar . '%');
        }
        return datatables()->of($operations)->toJson();
    }

    public function create()
    {
        return view('dashboard.operations.create');
    }

    public function store(Request $request)
    {
        $validator_array = [
            'name_en' => [
                'required',
                Rule::unique('operations'),
            ],
            'name_ar' => [
                'required',
                Rule::unique('operations'),
            ],
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $operation_array = [
            'name_en' => $request->input('name_en'),
            'name_ar' => $request->input('name_ar'),
            'description_en' => $request->input('description_en'),
            'description_ar' => $request->input('description_ar'),
        ];

        $operation_query = Operation::query();
        $created_operation = $operation_query->create($operation_array);

        if ($created_operation) {
            session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('operation.attribute_name')]));
            return redirect()->route('operation.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $operation = Operation::find($id);
        return view('dashboard.operations.edit')->with(['operation' => $operation]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'name_en' => [
                'required',
                Rule::unique('operations')->ignore($id),
            ],
            'name_ar' => [
                'required',
                Rule::unique('operations')->ignore($id),
            ],
        ];
        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $operation_array = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        $operation = Operation::find($id);
        $updated_operation = $operation->update($operation_array);

        if ($updated_operation) {
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('operation.attribute_name')]));
            return redirect()->route('operation.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

    }

    public function destroy($id, Request $request)
    {
        $operation = Operation::find($id);
        $deleted_operation = $operation->delete();

        if ($deleted_operation) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('operation.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('operation.attribute_name')]));
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
