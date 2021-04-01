<div class="form-body">
    {{--Question--}}
    <div class="form-group">
        <label class="label-control" for="question">@lang('faq.question')</label>
        <input type="text" id="question" class="form-control"
               placeholder="@lang('faq.question')" name="question"
               value="{{ old('question', isset($faq) ? $faq->question : '')}}">
        @if ($errors->has('question'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('question') }}
            </div>
        @endif
    </div>
    {{--Answer--}}
    <div class="form-group">
        <label for="answer">@lang('faq.answer')</label>
        <textarea id="answer" rows="5" class="form-control" name="answer"
                  placeholder="@lang('faq.answer')">{{ old('answer', isset($faq) ? $faq->answer : '')}}</textarea>
        @if ($errors->has('answer'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('answer') }}
            </div>
        @endif
    </div>

</div>
