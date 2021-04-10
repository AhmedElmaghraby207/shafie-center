<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            {{--name--}}
            <div class="form-group">
                <label for="name">@lang('notification_template.name')</label>
                <input type="text" id="name" class="form-control"
                       placeholder="@lang('notification_template.name')" name="name" readonly
                       value="{{ old('name', isset($notification_template) ? $notification_template->name : '')}}">
                @if ($errors->has('name'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            {{--subject--}}
            <div class="form-group">
                <label for="subject">@lang('notification_template.subject')</label>
                <textarea id="subject" rows="3" class="form-control" name="subject"
                          placeholder="@lang('notification_template.subject')">{{ old('subject', isset($notification_template) ? $notification_template->subject : '')}}</textarea>
                @if ($errors->has('subject'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('subject') }}
                    </div>
                @endif
            </div>
            {{--template--}}
            <div class="form-group">
                <label for="template">@lang('notification_template.template')</label>
                <textarea id="template" rows="5" class="form-control" name="template"
                          placeholder="@lang('notification_template.template')">{{ old('template', isset($notification_template) ? $notification_template->template : '')}}</textarea>
                @if ($errors->has('template'))
                    <div class="error" style="color: red">
                        <i class="fa fa-sm fa-times-circle"></i>
                        {{ $errors->first('template') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
