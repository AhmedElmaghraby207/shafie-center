<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Faq;
use App\Setting;
use App\Transformers\FaqTransformer;
use Illuminate\Http\Request;
use Spatie\Fractal\Facades\Fractal;

class FaqsController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function list(Request $request)
    {
        $keyword = $request->keyword;

        $faqs = Faq::query();
        if ($keyword) {
            $faqs = $faqs
                ->where('question_en', 'like', '%' . $keyword . '%')
                ->orWhere('question_ar', 'like', '%' . $keyword . '%')
                ->orWhere('answer_en', 'like', '%' . $keyword . '%')
                ->orWhere('answer_ar', 'like', '%' . $keyword . '%');
        }
        $faqs = $faqs->get();

        $faqs = Fractal::collection($faqs)
            ->transformWith(new FaqTransformer($this->lang, [
                'id', 'question', 'answer'
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        $pdf_url = Setting::where('key', 'pdf_file')->first()->value;
        $faqs['pdf_url'] = url($pdf_url);

        return response()->json($faqs);

    }
}
