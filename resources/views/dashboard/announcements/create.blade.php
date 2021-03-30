@extends('dashboard.layout.app')

@section('title') @lang('announcement.title_create') @endsection

@section('content_header')
    <div class="content-header-left col-md-6 col-12 mb-1">
        <h3 class="content-header-title">@lang('announcement.title_create')</h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <p>{{ Breadcrumbs::render('announcements-create') }}</p>
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content collpase show">
                    <div class="card-body">
                        <form class="form form-horizontal" method="POST"
                              action="{{ route('announcement.send') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{--Patients--}}
                                        <div class="form-group">
                                            <label for="patients">@lang('announcement.patients')</label>
                                            <select id="patients" name="patients" class="select2 form-control"
                                                    multiple="multiple">
                                                @foreach($patients as $patient)
                                                    <option
                                                        value="{{$patient->id}}">{{$patient->first_name}} {{$patient->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--Subject--}}
                                        <div class="form-group">
                                            <label for="subject">@lang('announcement.subject')</label>
                                            <input type="text" id="subject" class="form-control"
                                                   placeholder="@lang('announcement.subject')" name="subject">
                                            @if ($errors->has('subject'))
                                                <div class="error" style="color: red">
                                                    <i class="fa fa-sm fa-times-circle"></i>
                                                    {{ $errors->first('subject') }}
                                                </div>
                                            @endif
                                        </div>
                                        {{--message--}}
                                        <div class="form-group">
                                            <label for="message">@lang('announcement.message')</label>
                                            <textarea id="message" rows="5" class="form-control" name="message"
                                                      placeholder="@lang('announcement.message')">
                                            </textarea>
                                            @if ($errors->has('message'))
                                                <div class="error" style="color: red">
                                                    <i class="fa fa-sm fa-times-circle"></i>
                                                    {{ $errors->first('message') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row skin skin-flat">
                                            {{--mail_checkbox--}}
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <input type="checkbox" name="mail_checkbox" id="mail_checkbox">
                                                    <label for="mail_checkbox">@lang('announcement.mail_checkbox')</label>
                                                    @if ($errors->has('mail_checkbox'))
                                                        <div class="error" style="color: red">
                                                            <i class="fa fa-sm fa-times-circle"></i>
                                                            {{ $errors->first('mail_checkbox') }}
                                                        </div>
                                                    @endif
                                                </fieldset>
                                            </div>
                                            {{--notify_checkbox--}}
                                            <div class="col-md-3">
                                                <fieldset>
                                                    <input type="checkbox" name="notify_checkbox" id="notify_checkbox">
                                                    <label for="notify_checkbox">@lang('announcement.notify_checkbox')</label>
                                                    @if ($errors->has('notify_checkbox'))
                                                        <div class="error" style="color: red">
                                                            <i class="fa fa-sm fa-times-circle"></i>
                                                            {{ $errors->first('notify_checkbox') }}
                                                        </div>
                                                    @endif
                                                </fieldset>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-actions right">
                                <a href="{{url('/dashboard')}}" class="btn btn-warning mr-1">
                                    <i class="ft-x"></i> @lang('main.cancel_button')
                                </a>
                                <button type="submit" class="btn btn-primary" style="height: 40px">
                                    <i class="la la-check-square-o"></i> @lang('main.add_button')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
