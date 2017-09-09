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
                    <h4>REPORTS</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <a href="/admin/books"  class="btn btn-default btn-lg btn-block  btn-xlarge"><img src="/images/book.png" height="65" width="65"><br>
                    <span style="font-size: 10px;font-weight: bold;white-space: normal;" class="text text-success">Book Reports</span>
                </a>
                <br>
            </div>
            <div class="col-md-6">
                <a href="/admin/reports/attendance"  class="btn btn-default btn-lg btn-block  btn-xlarge"><img src="/images/attendance.png" height="65" width="65"><br>
                    <span style="font-size: 10px;font-weight: bold;white-space: normal;" class="text text-success">Attendance Reports</span>
                </a>
                <br>
            </div>
            <div class="col-md-6">
                <a href="/admin/reports/transaction-report"  class="btn btn-default btn-lg btn-block  btn-xlarge"><img src="/images/transaction.png" height="65" width="65"><br>
                    <span style="font-size: 10px;font-weight: bold;white-space: normal;" class="text text-success">Transaction Reports</span>
                </a>
                <br>
            </div>
            <div class="col-md-6">
                <a href="/admin/reports/borrower-report"  class="btn btn-default btn-lg btn-block  btn-xlarge"><img src="/images/borrower.png" height="65" width="65"><br>
                    <span style="font-size: 10px;font-weight: bold;white-space: normal;" class="text text-success">Borrower Reports</span>
                </a>
                <br>
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