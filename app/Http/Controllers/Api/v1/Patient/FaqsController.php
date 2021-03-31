<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Faq;
use App\Setting;
use App\Transformers\FaqTransformer;
use Illuminate\Http\Request;
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
            ->parseIncludes([])->toArray();

        $pdf_url = Setting::where('key', 'pdf_file')->first()->value;
        $faqs['pdf_url'] = url($pdf_url);

        return response()->json($faqs);

    }
}
