<?php

namespace App\Http\Controllers\Dashboard;

use App\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Input;

class FaqsController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->middleware('permission:faq-list', ['only' => ['index', 'getFaqs']]);
        $this->middleware('permission:faq-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:faq-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faq-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('dashboard.faqs.index');
    }

    public function getFaqs(Request $request)
    {
        $question = $request->question;
        $answer = $request->answer;

        $faqs = Faq::query();
        if ($question) {
            $faqs = $faqs->where('question', 'like', '%' . $question . '%');
        }
        if ($answer) {
            $faqs = $faqs->where('answer', 'like', '%' . $answer . '%');
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
            'question' => [
                'required',
                'max:1000',
                Rule::unique('faqs'),
            ],
            'answer' => 'required',
        ];

        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput(Input::all())->withErrors($validator);
            }
        }

        $faq_array = [
            'question' => $request->question,
            'answer' => $request->answer,
        ];

        $faq_query = Faq::query();
        $created_faq = $faq_query->create($faq_array);

        if ($created_faq) {
            session()->flash('success_message', trans('main.created_alert_message', ['attribute' => Lang::get('faq.attribute_name')]));
            return redirect()->route('faq.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput(Input::all())->withErrors($validator);
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
            'question' => [
                'required',
                'max:1000',
                Rule::unique('faqs')->ignore($id),
            ],
            'answer' => 'required',
        ];
        $validator = Validator::make($request->all(), $validator_array);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'fail', 'error_message' => 'validation error', 'errors' => $validator->errors()]);
            } else {
                return redirect()->back()->withInput(Input::all())->withErrors($validator);
            }
        }

        $faq_array = [
            'question' => $request->question,
            'answer' => $request->answer,
        ];

        $faq = Faq::find($id);
        $updated_faq = $faq->update($faq_array);

        if ($updated_faq) {
            session()->flash('success_message', trans('main.updated_alert_message', ['attribute' => Lang::get('faq.attribute_name')]));
            return redirect()->route('faq.index');
        } else {
            session()->flash('error_message', 'Something went wrong');
            return redirect()->back()->withInput(Input::all())->withErrors($validator);
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
