<div class="form-body">
    {{--question_en--}}
    <div class="form-group">
        <label class="label-control" for="question_en">@lang('faq.question_en')</label>
        <input type="text" id="question_en" class="form-control"
               placeholder="@lang('faq.question_en')" name="question_en"
               value="{{ old('question_en', isset($faq) ? $faq->question_en : '')}}">
        @if ($errors->has('question_en'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('question_en') }}
            </div>
        @endif
    </div>
    {{--question_ar--}}
    <div class="form-group">
        <label class="label-control" for="question_ar">@lang('faq.question_ar')</label>
        <input type="text" id="question_ar" class="form-control"
               placeholder="@lang('faq.question_ar')" name="question_ar"
               value="{{ old('question_ar', isset($faq) ? $faq->question_ar : '')}}">
        @if ($errors->has('question_ar'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('question_ar') }}
            </div>
        @endif
    </div>
    {{--answer_en--}}
    <div class="form-group">
        <label for="answer_en">@lang('faq.answer_en')</label>
        <textarea id="answer_en" rows="5" class="form-control" name="answer_en"
                  placeholder="@lang('faq.answer_en')">{{ old('answer_en', isset($faq) ? $faq->answer_en : '')}}</textarea>
        @if ($errors->has('answer_en'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('answer_en') }}
            </div>
        @endif
    </div>
    {{--answer_ar--}}
    <div class="form-group">
        <label for="answer_ar">@lang('faq.answer_ar')</label>
        <textarea id="answer_ar" rows="5" class="form-control" name="answer_ar"
                  placeholder="@lang('faq.answer_ar')">{{ old('answer_ar', isset($faq) ? $faq->answer_ar : '')}}</textarea>
        @if ($errors->has('answer_ar'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('answer_ar') }}
            </div>
        @endif
    </div>

</div>
