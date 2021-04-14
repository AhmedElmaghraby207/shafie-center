<?php

namespace App\Http\Controllers\Dashboard;

use App\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FaqsController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:faq-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:faq-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:faq-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faq-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('dashboard.faqs.index');
    }

    public function list(Request $request)
    {
        $question = $request->question;
        $answer = $request->answer;

        $faqs = Faq::query();
        if ($question) {
            $faqs = $faqs->where('question_en', 'like', '%' . $question . '%')
                ->orWhere('question_ar', 'like', '%' . $question . '%');
        }

        if ($answer) {
            $faqs = $faqs->where('answer_en', 'like', '%' . $answer . '%')
                ->orWhere('answer_ar', 'like', '%' . $answer . '%');
        }

        return datatables()->of($faqs)->toJson();
    }

    public function create()
    {
        return view('dashboard.faqs.create');
    }

    public function store(Request $request)
    {
        $validator_array = [
            'question_en' => [
                'required',
                'max:1000',
                Rule::unique('faqs'),
            ],
            'question_ar' => [
                'required',
                'max:1000',
                Rule::unique('faqs'),
            ],
            'answer_en' => 'required',
            'answer_ar' => 'required',
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $faq_array = [
            'question_en' => $request->question_en,
            'question_ar' => $request->question_ar,
            'answer_en' => $request->answer_en,
            'answer_ar' => $request->answer_ar,
        ];

        $faq_query = Faq::query();
        $created_faq = $faq_query->create($faq_array);

        if ($created_faq) {
            session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('faq.attribute_name')]));
            return redirect()->route('faq.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('dashboard.faqs.edit')->with(['faq' => $faq]);
    }

    public function update($id, Request $request)
    {
        $validator_array = [
            'question_en' => [
                'required',
                'max:1000',
                Rule::unique('faqs')->ignore($id),
            ],
            'question_ar' => [
                'required',
                'max:1000',
                Rule::unique('faqs')->ignore($id),
            ],
            'answer_en' => 'required',
            'answer_ar' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $faq_array = [
            'question_en' => $request->question_en,
            'question_ar' => $request->question_ar,
            'answer_en' => $request->answer_en,
            'answer_ar' => $request->answer_ar,
        ];

        $faq = Faq::find($id);
        $updated_faq = $faq->update($faq_array);

        if ($updated_faq) {
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('faq.attribute_name')]));
            return redirect()->route('faq.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

    }

    public function destroy($id, Request $request)
    {
        $faq = Faq::find($id);
        $deleted_faq = $faq->delete();

        if ($deleted_faq) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'success_message' => trans('main.deleted_alert_message', ['attribute' => Lang::get('faq.attribute_name')]),
                ]);
            }
            session()->flash('success_message', trans('main.deleted_alert_message', ['attribute' => Lang::get('faq.attribute_name')]));
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
