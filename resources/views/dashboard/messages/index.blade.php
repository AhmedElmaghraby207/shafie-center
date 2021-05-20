@extends('dashboard.layout.app')

@section('title') @lang('message.title_list') @endsection

@section('content_header')
    <div class="content-header-left col-md-6 col-12">
        <h3 class="content-header-title">@lang('message.title_list')</h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <p>{{ Breadcrumbs::render('messages') }}</p>
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
                            <form method="get" id="form_messages_search">
                                <div class="row">

                                    {{--Patients--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="patient_id">@lang('patient.attribute_name')</label>
                                            <select id="patient_id" name="patient_id" class="select2 form-control">
                                                <option value=""></option>
                                                @foreach($patients as $patient)
                                                    <option
                                                        value="{{$patient->id}}">{{$patient->first_name}} {{$patient->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--message search field--}}
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="message">@lang('message.message')</label>
                                            <input type="text" id="message" name="message"
                                                   class="form-control font-size-small"
                                                   placeholder="@lang('message.message')">
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
                        <h4 class="card-title display-inline">@lang('main.title_data')</h4>
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
                            <table class="table table-striped table-bordered file-export" id="messagesTable"
                                   style="width: 100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.name')</th>
                                    <th>@lang('message.message')</th>
                                    @if(session()->get('user_admin')->can('message-read'))
                                        <th>@lang('message.status')</th>
                                    @endif
                                    @if(session()->get('user_admin')->can('message-delete'))
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

        let message_field = $('#message');
        let patient_id_field = $('#patient_id');
        let form_messages_search = $('#form_messages_search');
        let clear_button = $('#clear_button');
        let reload_data_btn = $('#reload_data_btn');

        $(document).ready(function () {

            $('#patient_id').select2({
                placeholder: 'Select'
            })

            // Draw table after Filter
            form_messages_search.on('submit', function (e) {
                e.preventDefault();
                MessagesDatatable();
            });

            // Draw table after click reload btn
            reload_data_btn.click(MessagesDatatable);

            // Draw table after Clear
            clear_button.on('click', function (e) {
                message_field.val("");
                patient_id_field.prop('selectedIndex', 0);
                patient_id_field.select2({placeholder: 'Select'});
                form_messages_search.submit();
                check_inputs();
            });

            message_field.add(patient_id_field).bind("keyup change", function () {
                check_inputs();
            });

            check_inputs();
            MessagesDatatable();

            $(document).on('click', "#read_message_btn", function (e) {
                var btn = $(this);
                $.ajax({
                    url: "{{ url('/dashboard/message') }}/" + btn.attr('message_id') + "/read",
                    method: 'get',
                    dataType: 'json',
                    data: {},
                    success: function (data) {
                        MessagesDatatable();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            });

        });

        function MessagesDatatable() {
            $('#messagesTable').DataTable({
                "bDestroy": true,
                processing: true,
                serverSide: true,
                searching: false,
                "order": [[0, "asc"]],
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                sPaginationType: "full_numbers",
                "bStateSave": true,
                "fnStateSave": function (oSettings, oData) {
                    localStorage.setItem('messagesDataTables', JSON.stringify(oData));
                },
                "fnStateLoad": function (oSettings) {
                    return JSON.parse(localStorage.getItem('messagesDataTables'));
                },
                @if(App::isLocale('ar'))
                language: dataTablesArabicLocalization,
                @endif
                ajax: {
                    url: "{{ route('message.list') }}",
                    method: 'POST',
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        //for search
                        d.message = message_field.val();
                        d.patient_id = patient_id_field.val();
                    }
                },
                type: 'GET',
                columns: [
                    {
                        data: 'id', name: 'id',
                        "searchable": true,
                        "sortable": true,
                    },
                    {
                        data: 'id', name: 'id',
                        "searchable": true,
                        "sortable": true,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html(oData.patient.first_name + ' ' + oData.patient.last_name);
                        }
                    },
                    {
                        data: 'message', name: 'message',
                        "searchable": true,
                        "sortable": true,
                    },
                        @if(session()->get('user_admin')->can('message-read'))
                    {
                        "data": "id",
                        "searchable": false,
                        "sortable": false,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            if (oData.read_at == null) {
                                $(nTd).html(
                                    "<button message_id='" + oData.id + "' id='read_message_btn' class='btn btn-sm btn-success' title='@lang('message.read_no')'>" +
                                    "<i class='la la-check' style='font-size: 1rem'></i></button>"
                                );
                            } else {
                                $(nTd).html("<p class='text-success'>@lang('message.read_yes')</p>")
                            }

                        }
                    },
                        @endif
                        @if(session()->get('user_admin')->can('message-delete'))
                    {
                        "data": "id",
                        "searchable": false,
                        "sortable": false,
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html(
                                "<a href='javascript:' url='{{url('/dashboard/message/')}}/" + oData.id + "/delete' onclick='destroy(" + oData.id + ")' id='delete_" + oData.id + "' class='btn btn-danger btn-sm' title='@lang('main.delete_button')'><i class='fa fa-trash-alt'></i></a>"
                            );
                        }
                    }
                    @endif
                ]
            });
        }

        function check_inputs() {
            if (message_field.val().length > 0 || patient_id_field.val()) {
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
                                MessagesDatatable();
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
