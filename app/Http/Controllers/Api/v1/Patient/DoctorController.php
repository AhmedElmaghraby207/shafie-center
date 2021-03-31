<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Doctor;
use App\Setting;
use App\Transformers\DoctorTransformer;
use Illuminate\Http\Request;
use Spatie\Fractal\Facades\Fractal;

class DoctorController extends PatientApiController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function aboutUs(Request $request)
    {
        $doctor = Doctor::first();

        $doctor = Fractal::item($doctor)
            ->transformWith(new DoctorTransformer([
                'name', 'email', 'phone', 'clinic_name', 'about', 'image', 'about', 'signature', 'facebook', 'instagram', 'twitter', 'youtube', 'website'
            ]))
            ->parseIncludes([])->toArray();

        $pdf_url = Setting::where('key', 'pdf_file')->first()->value;
        $doctor['pdf_url'] = url($pdf_url);

        return response()->json($doctor);
    }
}
