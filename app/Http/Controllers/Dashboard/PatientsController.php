<?php

namespace App\Http\Controllers\Dashboard;

use App\Patient;
use App\PatientWeight;
use App\Repositories\Patients\PatientsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientsController extends BaseController
{
    protected $patientRep;

    function __construct(PatientsRepositoryInterface $patientRep)
    {
        parent::__construct();
        $this->middleware('permission:patient-list', ['only' => ['index', 'list', 'show']]);
        $this->middleware('permission:patient-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-edit', ['only' => ['edit', 'update', 'activate', 'deactivate']]);
        $this->middleware('permission:patient-delete', ['only' => ['destroy']]);
        $this->patientRep = $patientRep;
    }

    public function index()
    {
        return view('dashboard.patients.index');
    }

    public function list(Request $request)
    {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $status = $request->status;

        $patients = $this->patientRep->list(false, ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'status' => $status]);
        return datatables()->of($patients)->toJson();
    }

    public function show($id)
    {
        $patient = Patient::find($id);
        return view('dashboard.patients.show')->with(['patient' => $patient]);
    }

    public function create()
    {
        return view('dashboard.patients.create');
    }

    public function store(Request $request)
    {
        $validator_array = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'max:255',
                Rule::unique('patients'),
            ],
            'password' => 'required|confirmed|min:6',
            'age' => "required|numeric",
            'weight' => "required|numeric",
            'height' => "numeric",
            'gender' => "in:0,1",
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $patient_array = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => md5($request->password),
            'age' => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender' => $request->gender,
//            'address' => $request->address,
        ];

        $patient_query = Patient::query();
        $created_patient = $patient_query->create($patient_array);

        if ($created_patient) {
            if ($image = $request->image) {
                $path = 'uploads/patients/patient_' . $created_patient->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $created_patient->image = $path . $image_new_name;
                $created_patient->save();
            }
            if ($weight = $request->weight) {
                $patient_weight = [
                    'PatientId' => $created_patient->id,
                    'weight' => $weight
                ];
                PatientWeight::query()->create($patient_weight);
            }
            session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('patient.attribute_name')]));
            return redirect()->route('patient.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $patient = Patient::find($id);
        return view('dashboard.patients.edit')->with(['patient' => $patient]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'max:255',
                Rule::unique('patients')->ignore($id),
            ],
            'age' => "required|numeric",
            'weight' => "required|numeric",
            'height' => "numeric",
            'gender' => "in:0,1",
        ];

        if ($request->password) {
            $validator_array['password'] = 'required|confirmed|min:6';
        }

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $patient_array = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender' => $request->gender,
//            'address' => $request->address,
        ];
        if ($request->password) {
            $patient_array['password'] = md5($request->password);
        }

        $updated_patient = Patient::query()->find($id);
        $updated_patient->update($patient_array);
        if ($updated_patient) {
            if ($image = $request->image) {
                if ($updated_patient->image) {
                    @unlink($updated_patient->getOriginal('image'));
                }
                $path = 'uploads/patients/patient_' . $updated_patient->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $updated_patient->image = $path . $image_new_name;
                $updated_patient->save();
            }
            if ($weight = $request->weight) {
                $patient_weight = [
                    'PatientId' => $updated_patient->id,
                    'weight' => $weight
                ];
                PatientWeight::query()->create($patient_weight);
            }
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('patient.attribute_name')]));
            return redirect()->route('patient.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function activate($id, Request $request)
    {
        $patient = Patient::find($id);
        $patient->is_active = 1;
        $activated_patient = $patient->save();

        if ($activated_patient) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
            ]);
        }
    }

    public function deactivate($id, Request $request)
    {
        $patient = Patient::find($id);
        $patient->is_active = 0;
        $deactivated_patient = $patient->save();

        if ($deactivated_patient) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $patient = Patient::find($id);
        $deleted_patient = $patient->delete();

        if ($deleted_patient) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('patient.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('patient.attribute_name')]));
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
