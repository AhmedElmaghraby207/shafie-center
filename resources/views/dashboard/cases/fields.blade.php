<div class="form-body">
    {{--case_name--}}
    <div class="form-group">
        <label class="label-control" for="case_name">@lang('case.case_name')</label>
        <input type="text" id="case_name" class="form-control"
               placeholder="@lang('case.case_name')" name="case_name"
               value="{{ old('case_name', isset($case) ? $case->case_name : '')}}">
        @if ($errors->has('case_name'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('case_name') }}
            </div>
        @endif
    </div>
    <div class="row">
        {{--image_before--}}
        <div class="col-md-5">
            <h5>@lang('case.image_before')</h5>
            <div class="text-center mb-2">
                <img id="image_before_preview" style="height: 300px; width: 50%; border-radius: 2%"
                     src="{{isset($case) && $case->image_before ? url($case->image_before) : url('/uploads/defaults/case_before.jpg')}}"
                     alt="Card image">
            </div>
            <fieldset class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image_before"
                           id="image_before_to_preview">
                    <label class="custom-file-label"
                           for="image_before_to_preview">@lang('case.image_before')</label>
                </div>
                @if ($errors->has('image_before'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('image_before') }}
                    </div>
                @endif
            </fieldset>
        </div>
        {{--Spliter div--}}
        <div class="col-md-1 ml-auto mr-auto">
            <div style="background-color: #e9e9e9; width: 2%; height: 100%; margin: auto">
            </div>
        </div>
        {{--image_after--}}
        <div class="col-md-5">
            <h5>@lang('case.image_after')</h5>
            <div class="text-center mb-2">
                <img id="image_after_preview" style="height: 300px; width: 50%; border-radius: 2%"
                     src="{{isset($case) && $case->image_after ? url($case->image_after) : url('/uploads/defaults/case_after.jpg')}}"
                     alt="Card image">
            </div>
            <fieldset class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image_after"
                           id="image_after_to_preview">
                    <label class="custom-file-label"
                           for="image_after_to_preview">@lang('case.image_after')</label>
                </div>
                @if ($errors->has('image_after'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('image_after') }}
                    </div>
                @endif
            </fieldset>
        </div>
    </div>
    {{--description--}}
    <div class="form-group">
        <label for="description">@lang('case.description')</label>
        <textarea id="description" rows="5" class="form-control" name="description"
                  placeholder="@lang('case.description')">{{ old('description', isset($case) ? $case->description : '')}}</textarea>
        @if ($errors->has('description'))
            <div class="error" style="color: red">
                <i class="fa fa-sm fa-times-circle"></i>
                {{ $errors->first('description') }}
            </div>
        @endif
    </div>
</div>

@section('scripts')
    <script>
        let image_before_preview = $('#image_before_preview');
        let image_before_to_preview = $("#image_before_to_preview");

        image_before_to_preview.change(function () {
            readURL_image_before(this);
        });
        image_before_preview.on("click", function () {
            image_before_to_preview.click();
        });
        image_before_preview.on('mouseover', function () {
            $("#image_before_preview").css('cursor', 'pointer');
        });

        function readURL_image_before(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#image_before_preview").attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        //===========
        let image_after_preview = $('#image_after_preview');
        let image_after_to_preview = $('#image_after_to_preview');

        image_after_to_preview.change(function () {
            readURL_image_after(this);
        });
        image_after_preview.on("click", function () {
            image_after_to_preview.click();
        });
        image_after_preview.on('mouseover', function () {
            $("#image_after_preview").css('cursor', 'pointer');
        });

        function readURL_image_after(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#image_after_preview").attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
