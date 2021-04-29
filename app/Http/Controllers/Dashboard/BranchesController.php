<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranchesController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:branch-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:branch-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:branch-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('dashboard.branches.index');
    }

    public function list(Request $request)
    {
        $name = $request->name;
        $address = $request->address;

        $branches = Branch::query();
        if ($name) {
            $branches = $branches->where('name_en', 'like', '%' . $name . '%')
                ->orWhere('name_ar', 'like', '%' . $name . '%');
        }
        if ($address) {
            $branches = $branches->where('address_en', 'like', '%' . $address . '%')
                ->orWhere('address_ar', 'like', '%' . $address . '%');
        }
        return datatables()->of($branches)->toJson();
    }

    public function create()
    {
        return view('dashboard.branches.create');
    }

    public function store(Request $request)
    {
        $validator_array = [
            'name_en' => [
                'required',
                Rule::unique('branches'),
            ],
            'name_ar' => [
                'required',
                Rule::unique('branches'),
            ],
            'address_en' => 'required',
            'address_ar' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096'
        ];
        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $branch_array = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'address_en' => $request->address_en,
            'address_ar' => $request->address_ar,
            'phone' => $request->phone,
            'location_url' => $request->location_url
        ];

        $branch_query = Branch::query();
        $created_branch = $branch_query->create($branch_array);

        if ($created_branch) {
            if ($image = $request->image) {
                $path = 'uploads/branches/branch_' . $created_branch->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $created_branch->image = $path . $image_new_name;
                $created_branch->save();
            }
            session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('branch.attribute_name')]));
            return redirect()->route('branch.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $branch = Branch::find($id);
        return view('dashboard.branches.edit')->with(['branch' => $branch]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'name_en' => [
                'required',
                Rule::unique('branches')->ignore($id),
            ],
            'name_ar' => [
                'required',
                Rule::unique('branches')->ignore($id),
            ],
            'address_en' => 'required',
            'address_ar' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff|max:4096'
        ];
        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $branch_array = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'address_en' => $request->address_en,
            'address_ar' => $request->address_ar,
            'phone' => $request->phone,
            'location_url' => $request->location_url
        ];

        $branch = Branch::find($id);
        $updated_branch = $branch->update($branch_array);

        if ($updated_branch) {
            if ($image = $request->image) {
                $path = 'uploads/branches/branch_' . $branch->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $branch->image = $path . $image_new_name;
                $branch->save();
            }
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('branch.attribute_name')]));
            return redirect()->route('branch.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

    }

    public function destroy($id, Request $request)
    {
        $branch = Branch::find($id);
        $deleted_branch = $branch->delete();

        if ($deleted_branch) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('branch.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('branch.attribute_name')]));
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
