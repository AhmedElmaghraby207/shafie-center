<?php

namespace App\Http\Controllers\Dashboard;

use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class DoctorController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:doctor-show', ['only' => ['show']]);
        $this->middleware('permission:doctor-edit', ['only' => ['edit', 'update']]);
    }

    public function show()
    {
        $doctor = Doctor::first();
        return view('dashboard.doctor.show')->with(['doctor' => $doctor]);
    }

    public function edit()
    {
        $doctor = Doctor::first();
        return view('dashboard.doctor.edit')->with(['doctor' => $doctor]);
    }

    public function update($id, Request $request)
    {
        $updated_doctor = Doctor::query()->find($id);

        $validator_array = [
            'name_en' => 'required',
            'name_ar' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'about_en' => 'required',
            'about_ar' => 'required',
            'clinic_name_en' => 'required',
            'clinic_name_ar' => 'required',
            'facebook' => 'required',
            'instagram' => 'required',
            'website' => 'required',
        ];

        if (!$updated_doctor->image)
            $validator['image'] = 'required';

        if (!$updated_doctor->signature)
            $validator['image'] = 'required';

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $doctor_array = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'email' => $request->email,
            'phone' => $request->phone,
            'about_en' => $request->about_en,
            'about_ar' => $request->about_ar,
            'clinic_name_en' => $request->clinic_name_en,
            'clinic_name_ar' => $request->clinic_name_ar,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'twitter' => $request->twitter,
            'website' => $request->website,
        ];

        $updated_doctor->update($doctor_array);
        if ($updated_doctor) {
            if ($image = $request->image) {
                if ($updated_doctor->image) {
                    @unlink($updated_doctor->getOriginal('image'));
                }
                $path = 'uploads/doctor/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $updated_doctor->image = $path . $image_new_name;

                $updated_doctor->save();
            }
            if ($signature = $request->signature) {
                if ($updated_doctor->signature) {
                    @unlink($updated_doctor->getOriginal('signature'));
                }
                $path = 'uploads/doctor/';
                $signature_new_name = time() . '_' . $signature->getClientOriginalName();
                $signature->move($path, $signature_new_name);
                $updated_doctor->signature = $path . $signature_new_name;

                $updated_doctor->save();
            }

            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('doctor.attribute_name')]));
            return redirect()->route('doctor.show');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }
}
