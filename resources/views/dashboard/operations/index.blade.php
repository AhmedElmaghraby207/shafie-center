@extends('dashboard.layout.app')

@section('title') @lang('operation.title_list') @endsection

@section('content_header')
    <div class="content-header-left col-md-6 col-12">
        <h3 class="content-header-title">@lang('operation.title_list')</h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <p>{{ Breadcrumbs::render('operations') }}</p>
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
                            <form method="get" id="form_operations_search">
                                <div class="row">
                                    {{--name_en search field--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="name_en">@lang('operation.name_en')</label>
                                            <input type="text" id="name_en" name="name_en"
                                                   class="form-control font-size-small"
                                                   placeholder="@lang('operation.name_en')">
                                        </div>
                                    </div>
                                    {{--name_ar search field--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="name_ar">@lang('operation.name_ar')</label>
                                            <input type="text" id="name_ar" name="name_ar"
                                                   class="form-control font-size-small"
                                                   placeholder="@lang('operation.name_ar')">
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
                        <a class="btn add-btn btn-sm btn-success" href="{{ route('operation.create') }}">
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
                            <table class="table table-striped table-bordered file-export" id="operationsTable"
                                   style="width: 100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('operation.name_en')</th>
                                    <th>@lang('operation.name_ar')</th>
                                    <th>@lang('operation.description_en')</th>
                                    <th>@lang('operation.description_ar')</th>
                                    @if(session()->get('user_admin')->can('operation-edit') || session()->get('user_admin')->can('operation-delete'))
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

        let name_en_field = $('#name_en');
        let name_ar_field = $('#name_ar');
        let form_operations_search = $('#form_operations_search');
        let clear_button = $('#clear_button');
        let reload_data_btn = $('#reload_data_btn');

        $(document).ready(function () {

            // Draw table after Filter
            form_operations_search.on('submit', function (e) {
                e.preventDefault();
                operationsDatatable();
            });

            // Draw table after click reload btn
            reload_data_btn.click(operationsDatatable);

            // Draw table after Clear
            clear_button.on('click', function (e) {
                name_en_field.val("");
                name_ar_field.val("");
                form_operations_search.submit();
                check_inputs();
            });

            name_en_field.add(name_ar_field).bind("keyup change", function () {
                check_inputs();
            });

            check_inputs();
            operationsDatatable();
        });

        function operationsDatatable() {
            $('#operationsTable').DataTable({
                "bDestroy": true,
                processing: true,
                serverSide: true,
                searching: false,
                "order": [[0, "asc"]],
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                sPaginationType: "full_numbers",
                "bStateSave": true,
                "fnStateSave": function (oSettings, oData) {
                    localStorage.setItem('operationsDataTables', JSON.stringify(oData));
                },
                "fnStateLoad": function (oSettings) {
                    return JSON.parse(localStorage.getItem('operationsDataTables'));
                },
                @if(App::isLocale('ar'))
                language: dataTablesArabicLocalization,
                @endif
                ajax: {
                    url: "{{ route('operation.list') }}",
                    method: 'POST',
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        //for search
                        d.name_en = name_en_field.val();
                        d.name_ar = name_ar_field.val();
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
                        data: 'name_en', name: 'name_en',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'name_ar', name: 'name_ar',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'description_en', name: 'description_en',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'description_ar', name: 'description_ar',
                        "searchable": true,
                        "sortable": true,
                    },
                        @if(session()->get('user_admin')->can('operation-edit') || session()->get('user_admin')->can('operation-delete'))
                    {
                        "data": "id",
                        "searchable": false,
                        "sortable": false,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('')
                            @if(session()->get('user_admin')->can('operation-edit'))
                            $(nTd).append(
                                "<a href='{{url('dashboard/operation/')}}/" + oData.id + "/edit' class='btn btn-warning btn-sm' title='@lang('main.edit_button')'><i class='fa fa-edit'></i></a>  "
                            );
                            @endif
                            @if(session()->get('user_admin')->can('operation-delete'))
                            $(nTd).append(
                                "<a href='javascript:' url='{{url('dashboard/operation/')}}/" + oData.id + "/delete' onclick='destroy(" + oData.id + ")' id='delete_" + oData.id + "' class='btn btn-danger btn-sm' title='@lang('main.delete_button')'><i class='fa fa-trash-alt'></i></a>"
                            );
                            @endif
                        }
                    }
                    @endif
                ]
            });
        }

        function check_inputs() {
            if (name_en_field.val().length > 0 || name_ar_field.val().length > 0) {
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
                                operationsDatatable();
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
