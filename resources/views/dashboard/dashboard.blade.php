@extends('dashboard.layout.app')

@section('title') Home @endsection

@section('styles')
    @if(App::isLocale('en'))
        <link rel="stylesheet" type="text/css" href="{{url('/app-assets/css/pages/dashboard-ecommerce.css')}}">
    @elseif(App::isLocale('ar'))
        <link rel="stylesheet" type="text/css" href="{{url('/app-assets/css-rtl/pages/dashboard-ecommerce.css')}}">
    @endif

    <link rel="stylesheet" type="text/css" href="{{url('/app-assets/vendors/css/charts/morris.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/app-assets/vendors/css/charts/chartist.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{url('/app-assets/vendors/css/charts/chartist-plugin-tooltip.css')}}">
@endsection

@section('content_header')@endsection

@section('content')
    <!-- statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h2 class="success">{{$patients_count}}</h2>
                                <h6>@lang('patient.title_list')</h6>
                            </div>
                            <div>
                                <i class="fa fa-4x fa-user-tie success"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('patient.list') }}" target="_blank"
                               class="btn btn-success">@lang('dashboard.show_all_btn')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h2 class="info">{{$branches_count}}</h2>
                                <h6>@lang('branch.title_list')</h6>
                            </div>
                            <div>
                                <i class="fa fa-map-marker-alt fa-4x info"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('branch.list') }}" target="_blank"
                               class="btn btn-info">@lang('dashboard.show_all_btn')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h2 class="warning">{{$messages_count}}</h2>
                                <h6>@lang('message.title_list')</h6>
                            </div>
                            <div>
                                <i class="fa fa-envelope fa-4x warning"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('message.list') }}" target="_blank"
                               class="btn btn-warning">@lang('dashboard.show_all_btn')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h2 class="danger">{{$faqs_count}}</h2>
                                <h6>@lang('faq.title_list')</h6>
                            </div>
                            <div>
                                <i class="fa fa-question fa-4x danger"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('faq.list') }}" target="_blank"
                               class="btn btn-danger">@lang('dashboard.show_all_btn')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row match-height">
        <div class="col-xl-8 col-12" id="ecommerceChartView">
            <div class="card card-shadow">
                <div class="card-header card-header-transparent py-20">
                    <div class="btn-group dropdown">
                        <a href="#" class="text-body dropdown-toggle blue-grey-700" data-toggle="dropdown">PRODUCTS
                            SALES</a>
                        <div class="dropdown-menu animate" role="menu">
                            <a class="dropdown-item" href="#" role="menuitem">Sales</a>
                            <a class="dropdown-item" href="#" role="menuitem">Total sales</a>
                            <a class="dropdown-item" href="#" role="menuitem">profit</a>
                        </div>
                    </div>
                    <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group"
                        role="group">
                        <li class="nav-item"><a class="active nav-link" data-toggle="tab"
                                                href="#scoreLineToDay">Day</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToWeek">Week</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#scoreLineToMonth">Month</a>
                        </li>
                    </ul>
                </div>
                <div class="widget-content tab-content bg-white p-20">
                    <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay"></div>
                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek"></div>
                    <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth"></div>
                </div>
            </div>
        </div>

        {{-- latest patients table --}}
        <div class="col-xl-4 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('dashboard.new_patients')</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                </div>
                <div class="card-content">
                    <div id="new-orders" class="media-list position-relative">
                        <div class="table-responsive">
                            <table id="new-orders-table" class="table table-hover table-xl mb-0">
                                <thead>
                                <tr>
                                    <th class="border-top-0">@lang('patient.first_name')</th>
                                    <th class="border-top-0">@lang('patient.email')</th>
                                    <th class="border-top-0">@lang('dashboard.show_item_btn')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($latest_patients as $patient)
                                    <tr>
                                        <td class="text-truncate">{{$patient->first_name}}</td>
                                        <td class="text-truncate">{{$patient->email}}</td>
                                        <td class="text-truncate">
                                            <a href="{{ route('patient.show', $patient->id) }}" target="_blank"
                                               class="btn btn-sm btn-success">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{url('/app-assets/vendors/js/charts/chartist.min.js')}}" type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js')}}"
            type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/raphael-min.js')}}" type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/morris.min.js')}}" type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js')}}"
            type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js')}}"
            type="text/javascript"></script>
    <script src="{{url('/app-assets/js/scripts/pages/dashboard-ecommerce.js')}}" type="text/javascript"></script>
@endsection
