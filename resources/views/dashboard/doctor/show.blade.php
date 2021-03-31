@extends('dashboard.layout.app')

@section('title') @lang('doctor.title_show') @endsection

@section('content_header')
    <div class="content-header-left col-md-6 col-12 mb-1">
        <h3 class="content-header-title">@lang('doctor.title_show')
            <a class="btn btn-sm btn-warning" href="{{ route('doctor.edit') }}">
                <i class='la la-edit'></i> @lang('main.edit_button')
            </a>
        </h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <p>{{ Breadcrumbs::render('doctor-show') }}</p>
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
                        <div class="row">
                            <div class="col-md-8">
                                {{--Name--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-user"></i> @lang('doctor.name'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->name ? $doctor->name : '----'}}</span>
                                </div>
                                <hr>
                                {{--email--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-envelope"></i> @lang('doctor.email'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->email ? $doctor->email : '----'}}</span>
                                </div>
                                <hr>
                                {{--phone--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-phone"></i> @lang('doctor.phone'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->phone ? $doctor->phone : '----'}}</span>
                                </div>
                                <hr>
                                {{--clinic_name--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-hospital-o"></i> @lang('doctor.clinic_name'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->clinic_name ? $doctor->clinic_name : '----'}}</span>
                                </div>
                                <hr>
                                {{--about--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-info-circle"></i> @lang('doctor.about'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->about ? $doctor->about : '----'}}</span>
                                </div>
                                <hr>
                                {{--facebook--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-facebook"></i> @lang('doctor.facebook'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->facebook ? $doctor->facebook : '----'}}</span>
                                </div>
                                <hr>
                                {{--instagram--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-instagram"></i> @lang('doctor.instagram'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->instagram ? $doctor->instagram : '----'}}</span>
                                </div>
                                <hr>
                                {{--twitter--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-twitter"></i> @lang('doctor.twitter'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->twitter ? $doctor->twitter : '----'}}</span>
                                </div>
                                <hr>
                                {{--youtube--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-youtube"></i> @lang('doctor.youtube'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->youtube ? $doctor->youtube : '----'}}</span>
                                </div>
                                <hr>
                                {{--website--}}
                                <div class="form-group">
                                    <span class="text-primary"><i class="la la-globe"></i> @lang('doctor.website'):&nbsp;&nbsp; </span>
                                    <span>{{isset($doctor) && $doctor->website ? $doctor->website : '----'}}</span>
                                </div>
                            </div>

                            {{--Spliter div--}}
                            <div class="col-md-1 ml-auto mr-auto">
                                <div style="background-color: #e9e9e9; width: 2%; height: 100%; margin: auto">
                                </div>
                            </div>
                            {{--Image--}}
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="mb-1">
                                        <img id="image" style="height: 160px; width: 160px"
                                             src="{{isset($doctor) && $doctor->image ? url($doctor->image) : url('/app-assets/images/portrait/medium/avatar-m-4.png')}}"
                                             class="rounded-circle" alt="@lang('doctor.image')">
                                    </div>
                                    <span class="text-primary"><i class="la la-image"></i> @lang('doctor.image')</span>
                                </div>
                                <div class="mt-5 text-center">
                                    <div class="mb-1">
                                        <img id="signature_preview"
                                             style="height: 160px; width: 200px; border-radius: 10px"
                                             src="{{isset($doctor) && $doctor->signature ? url($doctor->signature) : url('/app-assets/images/portrait/medium/avatar-m-4.png')}}"
                                             class="" alt="@lang('doctor.signature')">
                                    </div>
                                    <span class="text-primary"><i class="fa fa-signature"></i> @lang('doctor.signature')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
