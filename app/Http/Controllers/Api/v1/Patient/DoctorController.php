<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Doctor;
use App\PatientCase;
use App\Transformers\CaseTransformer;
use App\Transformers\DoctorTransformer;
use Illuminate\Http\Request;
use Spatie\Fractal\Facades\Fractal;

class DoctorController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function aboutUs(Request $request)
    {
        $doctor = Doctor::first();

        $doctor->image = url($doctor->image);
        $doctor->signature = url($doctor->signature);

        $doctor = Fractal::item($doctor)
            ->transformWith(new DoctorTransformer($this->lang, [
                'name', 'email', 'phone', 'clinic_name', 'about', 'image', 'about', 'signature', 'facebook', 'instagram', 'twitter', 'youtube', 'website'
            ]))
            ->parseIncludes([])->toArray();

        $cases = Fractal::collection(PatientCase::all())
            ->transformWith(new CaseTransformer([
                'case_name', 'image_before', 'image_after'
            ]))
            ->parseIncludes([])->toArray();

        $doctor['data']['cases'] = $cases['data'];

        return response()->json($doctor);
    }
}
