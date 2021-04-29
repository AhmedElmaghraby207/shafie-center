<div class="form-body">
    <div class="row">
        <div class="col-md-8">
            {{--Name en--}}
            <div class="form-group">
                <label class="label-control" for="name_en">@lang('branch.name_en')</label>
                <input type="text" id="name_en" class="form-control"
                       placeholder="@lang('branch.name_en')" name="name_en"
                       value="{{ old('name_en', isset($branch) ? $branch->name_en : '')}}">
                @if ($errors->has('name_en'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('name_en') }}
                    </div>
                @endif
            </div>
            {{--Name ar--}}
            <div class="form-group">
                <label class="label-control" for="name_ar">@lang('branch.name_ar')</label>
                <input type="text" id="name_ar" class="form-control"
                       placeholder="@lang('branch.name_ar')" name="name_ar"
                       value="{{ old('name_ar', isset($branch) ? $branch->name_ar : '')}}">
                @if ($errors->has('name_ar'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('name_ar') }}
                    </div>
                @endif
            </div>
            {{--Phone--}}
            <div class="form-group">
                <label class="label-control" for="phone">@lang('branch.phone')</label>
                <input type="text" id="phone" class="form-control"
                       placeholder="@lang('branch.phone')" name="phone"
                       value="{{ old('phone', isset($branch) ? $branch->phone : '')}}">
                @if ($errors->has('phone'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('phone') }}
                    </div>
                @endif
            </div>
        </div>
        {{--Spliter div--}}
        <div class="col-md-1 ml-auto mr-auto">
            <div style="background-color: #e9e9e9; width: 2%; height: 100%; margin: auto">
            </div>
        </div>
        {{--Image--}}
        <div class="col-md-3">
            <div class="text-center mb-2">
                <img id="image_preview" style="height: 180px; width: 250px"
                     src="{{isset($branch) && $branch->image ? url($branch->image) : url('/uploads/defaults/location.png')}}"
                     class="rounded-circle" alt="Card image">
            </div>
            <fieldset class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image"
                           id="image_to_preview">
                    <label class="custom-file-label"
                           for="image">@lang('branch.image')</label>
                </div>
                @if ($errors->has('image'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('image') }}
                    </div>
                @endif
            </fieldset>
        </div>
    </div>
    {{--AddressEn--}}
    <div class="form-group">
        <label for="address_en">@lang('branch.address_en')</label>
        <textarea id="address_en" rows="5" class="form-control" name="address_en"
                  placeholder="@lang('branch.address_en')">{{ old('address_en', isset($branch) ? $branch->address_en : '')}}</textarea>
        @if ($errors->has('address_en'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('address_en') }}
            </div>
        @endif
    </div>
    {{--AddressAr--}}
    <div class="form-group">
        <label for="address_ar">@lang('branch.address_ar')</label>
        <textarea id="address_ar" rows="5" class="form-control" name="address_ar"
                  placeholder="@lang('branch.address_ar')">{{ old('address_ar', isset($branch) ? $branch->address_ar : '')}}</textarea>
        @if ($errors->has('address_ar'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('address_ar') }}
            </div>
        @endif
    </div>
    {{--location_url--}}
    <div class="form-group">
        <label for="location_url">@lang('branch.location_url')</label>
        <textarea id="location_url" rows="5" class="form-control" name="location_url"
                  placeholder="@lang('branch.location_url')">{{ old('location_url', isset($branch) ? $branch->location_url : '')}}</textarea>
        @if ($errors->has('location_url'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('location_url') }}
            </div>
        @endif
    </div>

</div>
