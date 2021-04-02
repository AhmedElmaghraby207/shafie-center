<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Patient;
use App\Transformers\PatientTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class PatientsController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function profile(Request $request)
    {
        if ($this->lang != 'en' && $this->lang != 'ar')
            return response()->json(['error' => 'invalid language code']);

        $patient = PatientAuth::patient();

        if (!$patient)
            return response()->json(['error' => [__('auth.invalid_token')]]);

        $patient = Fractal::item($patient)
            ->transformWith(new PatientTransformer([
                'id', 'first_name', 'last_name', 'email', 'phone', 'image',
                'age', 'weight', 'height', 'gender', 'address',
                'lang' => $this->lang
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        return response()->json($patient);
    }

    public function updateProfile(Request $request)
    {
        if ($this->lang != 'en' && $this->lang != 'ar')
            return response()->json(['error' => 'invalid language code']);

        $patient_auth = PatientAuth::patient();
        if (!$patient_auth)
            return response()->json(['error' => [__('auth.invalid_token')]]);

        $patient = Patient::find($patient_auth->id);

        $validator = Validator::make($request->all(), [
            'first_name' => "required",
            'last_name' => "required",
            'phone' => "required",
            'age' => "required",
            'weight' => "required",
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $patient_array = [
            'first_name' => $request->first_name ?: $patient->first_name,
            'last_name' => $request->last_name ?: $patient->last_name,
            'phone' => $request->phone ?: $patient->phone,
            'age' => $request->age ?: $patient->age,
            'weight' => $request->weight ?: $patient->height,
            'height' => $request->height ?: $patient->height,
            'gender' => $request->gender ?: $patient->gender,
            'address' => $request->address ?: $patient->address,
        ];

        $patient->update($patient_array);

        if ($patient) {
            if ($image = $request->image) {
                if ($patient->image) {
                    @unlink($patient->getOriginal('image'));
                }
                $path = 'uploads/patients/patient_' . $patient->id . '/';
                $image_new_name = time() . '_' . $image->getClientOriginalName();
                $image->move($path, $image_new_name);
                $patient->image = $path . $image_new_name;
                $patient->save();
            }

            $patient = Fractal::item($patient)
                ->transformWith(new PatientTransformer([
                    'id', 'first_name', 'last_name', 'email', 'phone', 'image',
                    'age', 'weight', 'height', 'gender', 'address',
                    'lang' => $this->lang
                ]))
                ->withResourceName('')
                ->parseIncludes([])->toArray();

            return response()->json($patient);
        } else {
            return response()->json(['error' => 'Can not update profile, please try again!']);
        }
    }
}
