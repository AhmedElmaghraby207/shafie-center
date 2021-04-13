<div class="form-body">
    {{--name_en--}}
    <div class="form-group">
        <label class="label-control" for="name_en">@lang('operation.name_en')</label>
        <input type="text" id="name_en" class="form-control"
               placeholder="@lang('operation.name_en')" name="name_en"
               value="{{ old('name_en', isset($operation) ? $operation->name_en : '')}}">
        @if ($errors->has('name_en'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('name_en') }}
            </div>
        @endif
    </div>
    {{--name_ar--}}
    <div class="form-group">
        <label class="label-control" for="name_ar">@lang('operation.name_ar')</label>
        <input type="text" id="name_ar" class="form-control"
               placeholder="@lang('operation.name_ar')" name="name_ar"
               value="{{ old('name_ar', isset($operation) ? $operation->name_ar : '')}}">
        @if ($errors->has('name_ar'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('name_ar') }}
            </div>
        @endif
    </div>
    {{--description_en--}}
    <div class="form-group">
        <label for="description_en">@lang('operation.description_en')</label>
        <textarea id="description_en" rows="5" class="form-control" name="description_en"
                  placeholder="@lang('operation.description_en')">{{ old('description_en', isset($operation) ? $operation->description_en : '')}}</textarea>
        @if ($errors->has('description_en'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('description_en') }}
            </div>
        @endif
    </div>
    {{--description_ar--}}
    <div class="form-group">
        <label for="description_ar">@lang('operation.description_ar')</label>
        <textarea id="description_ar" rows="5" class="form-control" name="description_ar"
                  placeholder="@lang('operation.description_ar')">{{ old('description_ar', isset($operation) ? $operation->description_ar : '')}}</textarea>
        @if ($errors->has('description_ar'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('description_ar') }}
            </div>
        @endif
    </div>

</div>
