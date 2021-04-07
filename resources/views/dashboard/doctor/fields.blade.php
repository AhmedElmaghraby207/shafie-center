<div class="form-body">
    <div class="row">
        <div class="col-md-8">
            {{--Name En--}}
            <div class="form-group">
                <label class="label-control" for="name_en">@lang('doctor.name_en')</label>
                <input type="text" id="name_en" class="form-control"
                       placeholder="@lang('doctor.name_en')" name="name_en"
                       value="{{ old('name_en', isset($doctor) ? $doctor->name_en : '')}}">
                @if ($errors->has('name_en'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('name_en') }}
                    </div>
                @endif
            </div>
            {{--Name Ar--}}
            <div class="form-group">
                <label class="label-control" for="name_ar">@lang('doctor.name_ar')</label>
                <input type="text" id="name_ar" class="form-control"
                       placeholder="@lang('doctor.name_ar')" name="name_ar"
                       value="{{ old('name_ar', isset($doctor) ? $doctor->name_ar : '')}}">
                @if ($errors->has('name_ar'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('name_ar') }}
                    </div>
                @endif
            </div>
            {{--Email--}}
            <div class="form-group">
                <label class="label-control"
                       for="email">@lang('doctor.email')</label>
                <input type="email" id="email" class="form-control"
                       placeholder="@lang('doctor.email')" name="email"
                       value="{{ old('email', isset($doctor) ? $doctor->email : '')}}">
                @if ($errors->has('email'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            {{--Phone--}}
            <div class="form-group">
                <label class="label-control"
                       for="phone">@lang('doctor.phone')</label>
                <input type="text" id="phone" class="form-control"
                       placeholder="@lang('doctor.phone')" name="phone"
                       value="{{ old('phone', isset($doctor) ? $doctor->phone : '')}}">
                @if ($errors->has('phone'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('phone') }}
                    </div>
                @endif
            </div>
            {{--clinic_name_en--}}
            <div class="form-group">
                <label class="label-control"
                       for="clinic_name_en">@lang('doctor.clinic_name_en')</label>
                <input type="text" id="clinic_name_en" class="form-control"
                       placeholder="@lang('doctor.clinic_name_en')" name="clinic_name_en"
                       value="{{ old('clinic_name_en', isset($doctor) ? $doctor->clinic_name_en : '')}}">
                @if ($errors->has('clinic_name_en'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('clinic_name_en') }}
                    </div>
                @endif
            </div>
            {{--clinic_name_ar--}}
            <div class="form-group">
                <label class="label-control"
                       for="clinic_name_ar">@lang('doctor.clinic_name_ar')</label>
                <input type="text" id="clinic_name_ar" class="form-control"
                       placeholder="@lang('doctor.clinic_name_ar')" name="clinic_name_ar"
                       value="{{ old('clinic_name_ar', isset($doctor) ? $doctor->clinic_name_ar : '')}}">
                @if ($errors->has('clinic_name_ar'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('clinic_name_ar') }}
                    </div>
                @endif
            </div>
            {{--about_en--}}
            <div class="form-group">
                <label for="about_en">@lang('doctor.about_en')</label>
                <textarea id="about_en" rows="5" class="form-control" name="about_en"
                          placeholder="@lang('doctor.about_en')">{{ old('about_en', isset($doctor) ? $doctor->about_en : '')}}</textarea>
                @if ($errors->has('about_en'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('about_en') }}
                    </div>
                @endif
            </div>
            {{--about_ar--}}
            <div class="form-group">
                <label for="about_ar">@lang('doctor.about_ar')</label>
                <textarea id="about_ar" rows="5" class="form-control" name="about_ar"
                          placeholder="@lang('doctor.about_ar')">{{ old('about_ar', isset($doctor) ? $doctor->about_ar : '')}}</textarea>
                @if ($errors->has('about_ar'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('about_ar') }}
                    </div>
                @endif
            </div>
            {{--facebook--}}
            <div class="form-group">
                <label class="label-control"
                       for="facebook">@lang('doctor.facebook')</label>
                <input type="url" id="facebook" class="form-control"
                       placeholder="@lang('doctor.facebook')" name="facebook"
                       value="{{ old('facebook', isset($doctor) ? $doctor->facebook : '')}}">
                @if ($errors->has('facebook'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('facebook') }}
                    </div>
                @endif
            </div>
            {{--instagram--}}
            <div class="form-group">
                <label class="label-control"
                       for="instagram">@lang('doctor.instagram')</label>
                <input type="url" id="instagram" class="form-control"
                       placeholder="@lang('doctor.instagram')" name="instagram"
                       value="{{ old('instagram', isset($doctor) ? $doctor->instagram : '')}}">
                @if ($errors->has('instagram'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('instagram') }}
                    </div>
                @endif
            </div>
            {{--twitter--}}
            <div class="form-group">
                <label class="label-control"
                       for="twitter">@lang('doctor.twitter')</label>
                <input type="url" id="twitter" class="form-control"
                       placeholder="@lang('doctor.twitter')" name="twitter"
                       value="{{ old('twitter', isset($doctor) ? $doctor->twitter : '')}}">
                @if ($errors->has('twitter'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('twitter') }}
                    </div>
                @endif
            </div>
            {{--youtube--}}
            <div class="form-group">
                <label class="label-control"
                       for="youtube">@lang('doctor.youtube')</label>
                <input type="url" id="youtube" class="form-control"
                       placeholder="@lang('doctor.youtube')" name="youtube"
                       value="{{ old('youtube', isset($doctor) ? $doctor->youtube : '')}}">
                @if ($errors->has('youtube'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('youtube') }}
                    </div>
                @endif
            </div>
            {{--website--}}
            <div class="form-group">
                <label class="label-control"
                       for="website">@lang('doctor.website')</label>
                <input type="url" id="website" class="form-control"
                       placeholder="@lang('doctor.website')" name="website"
                       value="{{ old('website', isset($doctor) ? $doctor->website : '')}}">
                @if ($errors->has('website'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('website') }}
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
            <div class="mt-3">
                <div class="text-center mb-2">
                    <img id="image_preview" style="height: 160px; width: 160px"
                         src="{{isset($doctor) && $doctor->image ? url($doctor->image) : url('/app-assets/images/portrait/medium/avatar-m-4.png')}}"
                         class="rounded-circle" alt="@lang('doctor.image')">
                </div>
                <fieldset class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image"
                               id="image_to_preview">
                        <label class="custom-file-label"
                               for="image">@lang('doctor.image')</label>
                    </div>
                    @if ($errors->has('image'))
                        <div class="error" style="color: red">
                            <i class="fa fa-sm fa-times-circle"></i>
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                </fieldset>
            </div>
            <div class="mt-5">
                <div class="text-center mb-2">
                    <img id="signature_preview" style="height: 160px; width: 200px; border-radius: 10px"
                         src="{{isset($doctor) && $doctor->signature ? url($doctor->signature) : url('/app-assets/images/portrait/medium/avatar-m-4.png')}}"
                         class="" alt="@lang('doctor.signature')">
                </div>
                <fieldset class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="signature"
                               id="signature">
                        <label class="custom-file-label"
                               for="signature">@lang('doctor.signature')</label>
                    </div>
                    @if ($errors->has('signature'))
                        <div class="error" style="color: red">
                            <i class="fa fa-sm fa-times-circle"></i>
                            {{ $errors->first('signature') }}
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $("#signature").change(function () {
            readURL_signature(this);
        });
        $("#signature_preview").on("click", function () {
            $("#signature").click();
        });
        $("#signature_preview").on('mouseover', function () {
            $("#signature_preview").css('cursor', 'pointer');
        });

        function readURL_signature(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#signature_preview").attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
