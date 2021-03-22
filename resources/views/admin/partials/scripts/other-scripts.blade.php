<script>
    let timeZone = 'Africa/Cairo';
    let momentFormat = {
        sameDay: '[Today at] h:m A',
        nextDay: '[Tomorrow at] h:m A',
        lastDay: '[Yesterday at] h:m A',
        lastWeek: 'D MMM YYYY - h:m A',
        sameElse: 'D MMM YYYY - h:m A'
    };
    let dataTablesArabicLocalization = {
        "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
        "sLoadingRecords": "جارٍ التحميل...",
        "sProcessing": "جارٍ التحميل...",
        "sLengthMenu": "أظهر _MENU_ مدخلات",
        "sZeroRecords": "لم يعثر على أية سجلات",
        "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
        "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
        "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
        "sInfoPostFix": "",
        "sSearch": "ابحث:",
        "sUrl": "",
        "oPaginate": {
            "sFirst": "الأول",
            "sPrevious": "السابق",
            "sNext": "التالي",
            "sLast": "الأخير"
        },
        "oAria": {
            "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
            "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
        }
    };

    $(document).ready(function () {
    });

    //Preview Image
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#image_preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image_to_preview").change(function () {
        readURL(this);
    });
    $("#image_preview").on("click", function () {
        $("#image_to_preview").click();
    });
    $("#image_preview").on('mouseover', function () {
        $("#image_preview").css('cursor', 'pointer');
    })

    //Disable Add & Save buttons
    {{--$('#add_btn').on('click', function () {--}}
    {{--    let btn = $(this);--}}
    {{--    $(btn).prop("disabled", true);--}}
    {{--    // add spinner to button--}}
    {{--    $(btn).html(--}}
    {{--        `<i class="fa fa-circle-o-notch fa-spin"></i> @lang('main.saving_button')`--}}
    {{--    );--}}
    {{--    $("#add_form").submit();--}}
    {{--});--}}
    {{--$('#update_btn').on('click', function () {--}}
    {{--    let btn = $(this);--}}
    {{--    $(btn).prop("disabled", true);--}}
    {{--    // add spinner to button--}}
    {{--    $(btn).html(--}}
    {{--        `<i class="fa fa-circle-o-notch fa-spin"></i> @lang('main.saving_button')`--}}
    {{--    );--}}
    {{--    $("#update_form").submit();--}}
    {{--});--}}

    {{--function destroy(id) {--}}
    {{--    swal({--}}
    {{--        title: "@lang('main.confirm_delete_question')",--}}
    {{--        text: "@lang('main.confirm_delete_message')",--}}
    {{--        type: "warning",--}}
    {{--        showCancelButton: true,--}}
    {{--        confirmButtonColor: "#f2af4e",--}}
    {{--        confirmButtonText: "@lang('main.button_delete_yes')",--}}
    {{--        cancelButtonText: "@lang('main.button_delete_no')",--}}
    {{--        closeOnConfirm: false,--}}
    {{--        closeOnCancel: false--}}
    {{--    }, function (isConfirm) {--}}
    {{--        if (isConfirm) {--}}
    {{--            swal("@lang('main.delete_success_title')", "@lang('main.delete_success_content')", "success");--}}
    {{--            window.location.href = $('#delete_' + id).attr('url');--}}
    {{--        } else {--}}
    {{--            swal.close();--}}
    {{--        }--}}
    {{--    });--}}
    {{--}--}}

    {{--function checkHasRelations(id) {--}}
    {{--    swal({--}}
    {{--        title: "@lang('main.confirm_delete_question')",--}}
    {{--        text: "@lang('main.confirm_delete_message')",--}}
    {{--        type: "warning",--}}
    {{--        showCancelButton: true,--}}
    {{--        confirmButtonColor: "#f2af4e",--}}
    {{--        confirmButtonText: "@lang('main.button_delete_yes')",--}}
    {{--        cancelButtonText: "@lang('main.button_delete_no')",--}}
    {{--        closeOnConfirm: false,--}}
    {{--        closeOnCancel: false--}}
    {{--    }, function (isConfirm) {--}}
    {{--        if (isConfirm) {--}}
    {{--            $.ajax({--}}
    {{--                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},--}}
    {{--                type: 'POST',--}}
    {{--                url: $('#delete_' + id).attr('relation_url'),--}}
    {{--                data: {},--}}
    {{--                success: function (data) {--}}
    {{--                    if (data.data === true) {--}}
    {{--                        swal("@lang('main.delete_fail_title')", "@lang('main.delete_fail_content')", "error");--}}
    {{--                    } else if (data.data === false) {--}}
    {{--                        swal("@lang('main.delete_success_title')", "@lang('main.delete_success_content')", "success");--}}
    {{--                        window.location.href = $('#delete_' + id).attr('url');--}}
    {{--                    }--}}
    {{--                },--}}
    {{--                error: function (xhr, status, error) {--}}
    {{--                    alert('Sorry, Server error happened!');--}}
    {{--                    console.log(error);--}}
    {{--                }--}}
    {{--            });--}}
    {{--        } else {--}}
    {{--            swal.close();--}}
    {{--        }--}}
    {{--    });--}}
    {{--}--}}

    let msg;
    @if(Session::has('success_message'))
        msg = '{{ Session::get('success_message') }}' + ' ';
    {{ Session::forget('success_message') }}
    toastr.success(msg, null, {"closeButton": true});
    @endif

        @if(Session::has('error_message'))
        msg = '{{ Session::get('error_message') }}' + ' ';
    {{ Session::forget('error_message') }}
    toastr.error(msg, null, {"closeButton": true});
    @endif

</script>
