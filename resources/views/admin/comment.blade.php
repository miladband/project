@extends("admin.base.base")
@section('title')
    مدیریت نظرات
@endsection
@section('css')
    <link rel="stylesheet" href="/css/loader.css"/>
    <script src="{{asset('dist/js/pagination.js')}}" type="text/javascript"></script>
@endsection
@section('header')
    مدیریت نظرات
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
            </div>
            <div class="card">
                <div class="card-header" data-background-color="purple">
                    <h4 class="title">مدیریت محتوی نظرات</h4>
                    <p class="category">لیست نظرات های موجود در سایت</p>
                </div>
                <div class="card-content">
                    <div class="col-md-10 col-md-offset-1 col-xs-12">
                        <div class="row">
                            <div class="colxs-12 col-md-2 pull-right">
                                <label>جستجو :</label>
                            </div>
                            <div class="col-xs-12 col-md-10 pull-right">
                                <input id="myInput" onkeyup="myFunction()" name="search_news" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                @if($comments == false)
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 pull-right">
                                            <div class="alert alert-info alert-with-icon" data-notify="container">
                                                <i data-notify="icon" class="flaticon-info-sign"></i>
                                                <span data-notify="message">هیچ خبری یافت نشد. لطفا نظر جدیدی وارد کنید.</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table id="myTable" class="table">
                                            <thead class="text-primary">
                                            <tr>
                                                <th class="col-xs-1 text-right">ردیف</th>
                                                <th class="col-xs-1 text-right">نام</th>
                                                <th class="col-xs-1 text-right">ایمیل</th>
                                                <th class="col-xs-2 text-right">موضوع </th>
                                                <th class="col-xs-4 text-right">پیام </th>
                                                <th class="col-xs-1 text-right">عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody data-content="content_table">
                                            @foreach($comments as $comment)
                                                <tr data-status="" data-id="{{$comment->id}}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td data-id="{{$comment->id}}" class="">{{$comment->name}}</td>
                                                    <td class="">{{$comment->email}}</td>
                                                    <td class="">{{$comment->subject}}</td>
                                                    <td class="">{{$comment->message}}</td>
                                                    <td class="actional">
                                                        <span data-id="{{$comment->id}}" data-title="delete_comments"
                                                              onclick="confirmDelete('{{ route('admin.comment.destroy', $comment->id) }}', '{{ $comment->id }}')"
                                                              class="flaticon-trash-2 delete_news_button"
                                                              data-toggle="tooltip" title="حذف"></span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center" style="direction:ltr">
                                        {{ $comments->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="_alert_">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times-circle"></i>&nbsp;</button>
                    <h4 class="modal-title">پیغام</h4>
                </div>
                <div class="modal-body alert_modal_class">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <div id="not_valid_Guarantee" style="display: none" class="alert alert-danger"></div>
                    <input type="button" class="btn btn-danger" data-dismiss="modal" value="بستن">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="/js/modal-generator.js"></script>
    <script>
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }

        }

        function confirmDelete(url, mId) {
            event.preventDefault();
            var btn = [
                '<button type="button" onclick="deleteRecord(\'' + url + '\',' + mId + ')" class="btn btn-warning pull-left">حذف</button>'
            ];
            showSimpleModal({
                id: 'myModal' + mId,
                size: 'small',
                title: 'توجه',
                body: 'آیا برای حذف مطمئن هستید؟',
                buttons: btn
            });
        }


        function deleteRecord(url, mId) {
            $('#myModal' + mId).removeClass('fade').modal('hide');
            $.ajax({
                url: url,
                method: 'DELETE',
                async: true,
                cache: false,
                beforeSend: function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    $('.loader').show();
                },
                complete: function () {
                    $('.loader').hide();
                },
                success: function (data) {
                    if (data.status) {
                        $('tr[data-id=' + mId + ']').remove();
                        showSimpleModal({
                            id: 'success-modal',
                            size: 'small',
                            title: 'توجه',
                            body: 'با موفقیت حذف شد'
                        });
                    } else {
                        showSimpleModal({
                            id: 'error-modal',
                            size: 'small',
                            title: 'توجه',
                            body: data.message
                        });
                    }
                },
                error: function (request, msg, error) {
                    console.log(request, msg, error);
                    showSimpleModal({
                        id: 'error-modal',
                        size: 'small',
                        title: 'توجه',
                        body: 'خطایی رخ داده است'
                    });
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
@endsection