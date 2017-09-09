@extends('layout')

@section('internal_stylesheet')
    <style type="text/css">
        .self-class {
            font-size: 12px;
            height:100%;
        }
        .search-panel {
            color: #3c763d;
        }

        .btn-xlarge {
            padding: 15px 25px;
            height: 165px;
            border-color: #3c763d;
        }
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>BORROWER REPORT</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-sm-12">
                <a href="/admin/reports/list/borrower-report" class="btn btn-primary btn-success pull-right">Download Borrower Reports</a>
            </div>

            <div class="col-sm-12 self-class">

                <hr>
                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Filter</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/reports/borrower/filter')->id('filters') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::date('From', 'created_at_from') !!}
                    {!! BootForm::date('To', 'created_at_to') !!}
                    {!! BootForm::select('Users', 'user_id', ['' => '-- Select One --'] + $users)->style('width:100%') !!}
                    {!! BootForm::submit('Generate')->addClass('btn btn-success') !!}
                </div>
                <div class="col-sm-9">
                    {!! BootForm::button('Queue Borrower Report', 'download')->id('download-pdf')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                    <br><br>
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr>
                                <th>UserID</th>
                                <th>Username</th>
                                <th>Book Borrowed</th>
                                <th>Borrowed At</th>
                            </tr>
                            @forelse ($data as $value)
                                <tr>
                                    <td>{{ $value->user->user_id }}</td>
                                    <td>{{ $value->user->name }}</td>
                                    <td>{{ $value->book->title }}</td>
                                    <td>{{ $value->reserved_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="4" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                            <tr>
                                <th colspan="4" style="text-align: right">Total Books Borrowed: {{ $data->total() }}</th>
                            </tr>
                        </table>

                        <div class="text-center">
                            {!! $data->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br><br>
@endsection

@section('page_js')
    <script type="text/javascript">
        $(window).load(function(){
            $('.btn-xlarge').hover(function() {
                $(this).css('border-color', '#3c763d');
            });
        });

        $("#download-pdf").click(function() {
            var form_data   = $("#filters").serialize();
            $.get("/admin/reports/download/borrower-report", form_data, function(data) {
                if (data.success == true) {
                    swal({title: "Success",   text: data.message, type: "success", showConfirmButton: false, timer: 3000});
                }
            });
        });

    </script>
@stop