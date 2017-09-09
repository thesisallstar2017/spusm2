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
                    <h4>PENALTY REPORT</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <a href="/admin/reports/list/penalty-report" class="btn btn-primary btn-success pull-right">Download Penalty Reports</a>
            </div>

            <div class="col-sm-12 self-class">
                <hr>
                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Filter</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/reports/penalty/filter')->id('filters') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::date('From', 'created_at_from') !!}
                    {!! BootForm::date('To', 'created_at_to') !!}
                    {!! BootForm::select('Users', 'user_id', ['' => '-- Select One --'] + $users)->style('width:100%') !!}
                    {!! BootForm::submit('Generate')->addClass('btn btn-success') !!}
                </div>
                <div class="col-sm-9">
                    {!! BootForm::button('Queue Transaction Report', 'download')->id('download-pdf')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                    <br><br>
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr>
                                <th>Username</th>
                                <th>Book Title</th>
                                <th>Is Damaged</th>
                                <th>Is Rejected</th>
                                <th>Is Expired</th>
                                <th>Is Damaged</th>
                                <th>Is Overdue</th>
                                <th>Is Lost</th>
                                <th>Penalty Amount</th>
                                <th>Overdue Day Count</th>
                                <th>Is Paid</th>
                            </tr>
                            @forelse ($data as $value)
                                <tr>
                                    <td>{{ $value->user->name }}</td>
                                    <td>{{ $value->book->title }}</td>
                                    <td>{{ $value->is_damaged }}</td>
                                    <td>{{ $value->is_rejected }}</td>
                                    <td>{{ $value->is_expired }}</td>
                                    <td>{{ $value->is_damaged }}</td>
                                    <td>{{ $value->is_overdue }}</td>
                                    <td>{{ $value->is_lost }}</td>
                                    <td>{{ @$value->fees[0]->amount }}</td>
                                    <td>{{ @$value->fees[0]->overdue_day_counts == 0 ? 1 : $value->fees[0]->overdue_day_counts }}</td>
                                    <td>{{ @$value->fees[0]->is_paid }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="11" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
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
            console.log(form_data);
            $.get("/admin/reports/download/penalty-report", form_data, function(data) {
                if (data.success == true) {
                    swal({title: "Success",   text: data.message, type: "success", showConfirmButton: false, timer: 3000});
                }
            });
        });

        $("#user_id").select2({
            placeholder: "-- Select Name --",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: '/users/search',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                }
            }
        });

    </script>
@stop