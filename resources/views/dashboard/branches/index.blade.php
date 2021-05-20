@extends('dashboard.layout.app')

@section('title') @lang('branch.title_list') @endsection

@section('content_header')
    <div class="content-header-left col-md-6 col-12">
        <h3 class="content-header-title">@lang('branch.title_list')</h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <p>{{ Breadcrumbs::render('branches') }}</p>
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css"
          href="{{url('/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection

@section('content')
    <section id="search">
        <div class="row">
            <div class="col-12">
                <div class="card" style="margin-bottom: 15px">
                    <div class="card-header">
                        <h4 class="card-title">@lang('main.title_search')</h4>
                        <a class="heading-elements-toggle"><i
                                class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard" style="padding: 0 1.5rem;">
                            <form method="get" id="form_branches_search">
                                <div class="row">
                                    {{--Name search field--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="name">@if(App::isLocale('en')) @lang('branch.name_en') @else @lang('branch.name_ar') @endif</label>
                                            <input type="text" id="name" name="name"
                                                   class="form-control font-size-small"
                                                   placeholder="@if(App::isLocale('en')) @lang('branch.name_en') @else @lang('branch.name_ar') @endif">
                                        </div>
                                    </div>
                                    {{--Address search field--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">@if(App::isLocale('en')) @lang('branch.address_en') @else @lang('branch.address_ar') @endif</label>
                                            <input type="text" id="address" name="address"
                                                   class="form-control font-size-small"
                                                   placeholder="@if(App::isLocale('en')) @lang('branch.address_en') @else @lang('branch.address_ar') @endif">
                                        </div>
                                    </div>
                                    {{--Filter & clrear buttons--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6 form-group" style="margin-top: 35px">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-filter"></i> @lang('main.filter_button')
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" id="clear_button" hidden>
                                            <i class="fa fa-times"></i> @lang('main.clear_button')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="data">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-header">
                        <h4 class="card-title display-inline">@lang('main.title_data')</h4>&ensp;&ensp;&ensp;
                        <a class="btn add-btn btn-sm btn-success" href="{{ route('branch.create') }}">
                            <i class="fa fa-plus"></i> @lang('main.add_button')
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload" id="reload_data_btn"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard pt-0">
                            <table class="table table-striped table-bordered file-export" id="branchesTable"
                                   style="width: 100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('branch.image')</th>
                                    <th>@if(App::isLocale('en')) @lang('branch.name_en') @else @lang('branch.name_ar') @endif</th>
                                    <th>@if(App::isLocale('en')) @lang('branch.address_en') @else @lang('branch.address_ar') @endif</th>
                                    <th>@lang('branch.phone')</th>
                                    <th>@lang('branch.location')</th>
                                    @if(session()->get('user_admin')->can('branch-edit') || session()->get('user_admin')->can('branch-delete'))
                                        <th>@lang('main.table_actions')</th>
                                    @endif
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{url('/app-assets/vendors/js/tables/datatable/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{url('/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"
            type="text/javascript"></script>
    <script src="{{url('/app-assets/js/scripts/tables/datatables/datatable-advanced.js')}}"
            type="text/javascript"></script>

    <script>

        let name_field = $('#name');
        let address_field = $('#address');
        let form_branches_search = $('#form_branches_search');
        let clear_button = $('#clear_button');
        let reload_data_btn = $('#reload_data_btn');

        $(document).ready(function () {

            // Draw table after Filter
            form_branches_search.on('submit', function (e) {
                e.preventDefault();
                branchesDatatable();
            });

            // Draw table after click reload btn
            reload_data_btn.click(branchesDatatable);

            // Draw table after Clear
            clear_button.on('click', function (e) {
                name_field.val("");
                address_field.val("");
                form_branches_search.submit();
                check_inputs();
            });

            name_field.add(address_field).bind("keyup change", function () {
                check_inputs();
            });

            check_inputs();
            branchesDatatable();
        });

        function branchesDatatable() {
            $('#branchesTable').DataTable({
                "bDestroy": true,
                processing: true,
                serverSide: true,
                searching: false,
                "order": [[0, "asc"]],
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                sPaginationType: "full_numbers",
                "bStateSave": true,
                "fnStateSave": function (oSettings, oData) {
                    localStorage.setItem('branchesDataTables', JSON.stringify(oData));
                },
                "fnStateLoad": function (oSettings) {
                    return JSON.parse(localStorage.getItem('branchesDataTables'));
                },
                @if(App::isLocale('ar'))
                language: dataTablesArabicLocalization,
                @endif
                ajax: {
                    url: "{{ route('branch.list') }}",
                    method: 'POST',
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        //for search
                        d.name = name_field.val();
                        d.address = address_field.val();
                    }
                },
                type: 'GET',
                columns: [
                    {
                        data: 'id', name: 'id',
                        "searchable": true,
                        "sortable": true,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html(oData.id);
                        }
                    },
                    {
                        data: 'image', name: 'image',
                        "searchable": true,
                        "sortable": true,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html(
                                '<img alt="branch image" style="width: 36px; height: 36px" src="' + oData.image + '" data-id="' + oData.id + '" class="img-fluid img-thumbnail">'
                            );
                        }
                    },
                        @if(App::isLocale('en'))
                    {
                        data: 'name_en', name: 'name_en',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'address_en', name: 'address_en',
                        "searchable": true,
                        "sortable": true,
                    },
                        @elseif(App::isLocale('ar'))
                    {
                        data: 'name_ar', name: 'name_ar',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'address_ar', name: 'address_ar',
                        "searchable": true,
                        "sortable": true,
                    },
                        @endif
                    {
                        data: 'phone', name: 'phone',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'location_url', name: 'location_url',
                        "searchable": true,
                        "sortable": true,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            if (oData.location_url) {
                                $(nTd).html("<a href='" + oData.location_url + "' class='btn btn-sm btn-primary' title='@lang('branch.location_url')' target='_blank'><i class='fa fa-location-arrow'></i></a>");
                            } else {
                                $(nTd).html("----");
                            }
                        }
                    },
                        @if(session()->get('user_admin')->can('branch-edit') || session()->get('user_admin')->can('branch-delete'))
                    {
                        "data": "id",
                        "searchable": false,
                        "sortable": false,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('')
                            @if(session()->get('user_admin')->can('branch-edit'))
                            $(nTd).append(
                                "<a href='{{url('dashboard/branch/')}}/" + oData.id + "/edit' class='btn btn-warning btn-sm' title='@lang('main.edit_button')'><i class='fa fa-edit'></i></a>  "
                            );
                            @endif
                            @if(session()->get('user_admin')->can('branch-delete'))
                            $(nTd).append(
                                "<a href='javascript:' url='{{url('dashboard/branch/')}}/" + oData.id + "/delete' onclick='destroy(" + oData.id + ")' id='delete_" + oData.id + "' class='btn btn-danger btn-sm' title='@lang('main.delete_button')'><i class='fa fa-trash-alt'></i></a>"
                            );
                            @endif
                        }
                    }
                    @endif
                ]
            });
        }

        function check_inputs() {
            if (name_field.val().length > 0 || address_field.val().length > 0) {
                clear_button.attr('hidden', false)
            } else {
                clear_button.attr('hidden', true)
            }
        }

        function destroy(id) {
            swal({
                title: "@lang('main.confirm_delete_question')",
                text: "@lang('main.confirm_delete_message')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f2af4e",
                confirmButtonText: "@lang('main.button_delete_yes')",
                cancelButtonText: "@lang('main.button_delete_no')",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'GET',
                        url: $('#delete_' + id).attr('url'),
                        data: {},
                        success: function (response) {
                            if (response.status === 'success') {
                                swal("@lang('main.delete_success_title')", "@lang('main.delete_success_content')", "success");
                                branchesDatatable();
                            } else if (response.status === 'fail') {
                                swal("@lang('main.delete_fail_title')", "@lang('main.delete_fail_content')", "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Sorry, Server error happened!');
                            console.log(error);
                        }
                    });
                } else {
                    swal.close();
                }
            });
        }

    </script>

@endsection
