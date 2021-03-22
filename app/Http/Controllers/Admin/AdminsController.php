<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }

    public function edit($id)
    {
        $admin = Admin::find($id);
        return view('admin.admins.edit')->with(['admin' => $admin]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'name' => 'required',
            'email' => [
                'required',
                'max:255',
                Rule::unique('admins')->ignore($id),
            ],
        ];

        if ($request->password) {
            $validator_array['password'] = 'required|confirmed|min:6';
        }

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput(Input::all())->withErrors($validator);
            }
        }

        $admin_array = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->password) {
            $admin_array['password'] = md5($request->password);
        }

        $updated_admin = Admin::query()->find($id);
        $updated_admin->update($admin_array);
        if ($updated_admin) {
            if ($image = $request->image) {
                if ($updated_admin->image) {
                    @unlink($updated_admin->getOriginal('image'));
                }
                $path = 'uploads/admins/admin_' . $updated_admin->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $updated_admin->image = $path . $image_new_name;

                $updated_admin->save();
            }

            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('employee.attribute_name')]));
            return redirect()->back();
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput(Input::all())->withErrors($validator);
        }


    }

}
