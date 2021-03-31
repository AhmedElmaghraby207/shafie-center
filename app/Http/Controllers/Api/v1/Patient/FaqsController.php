<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Facades\PatientAuthenticateFacade as PatientAuth;
use App\Faq;
use App\Transformers\FaqTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class FaqsController extends PatientApiController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function list(Request $request)
    {
        $keyword = $request->keyword;

        $faqs = Faq::query();
        if ($keyword) {
            $faqs = $faqs->where('question', 'like', '%' . $keyword . '%')
            ->orWhere('answer', 'like', '%' . $keyword . '%');
        }
        $faqs = $faqs->get();

        $faqs = Fractal::collection($faqs)
            ->transformWith(new FaqTransformer([
                'id', 'question', 'answer'
            ]))
            ->withResourceName('')
            ->parseIncludes([]);

        return response()->json($faqs);

    }
}
