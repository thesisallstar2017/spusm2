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
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    @if (Auth::user()->hasRole('admin'))
                        <h4>TRANSACTIONS</h4>
                    @else
                        <h4>MY TRANSACTIONS</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 self-class">
                {{-- Transaction Details Modal --}}
                <div id="view-details" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h5 class="modal-title">TRANSACTION DETAILS</h5>
                                </div>
                                <div class="modal-body">
                                    {{--<  small>--}}
                                        <div id="modal-details">

                                        </div>
                                    {{--</small>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Return Books Modal --}}

                <div id="return-book" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">RETURN BOOK</h5>
                            </div>
                            <div class="modal-body">
                                <p></p>
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-6">--}}

                                <div id="alertArea">

                                </div>
                                {{--<div class="alert alert-danger">--}}
                                    {{--<strong>Error!</strong> <span id="errorMsg"></span>--}}
                                {{--</div>--}}
                                {!! BootForm::openHorizontal(['md' => [3, 9]])->id('process-payment') !!}
                                        {!! BootForm::hidden('trans-id') !!}
                                        {!! BootForm::hidden('initial-amount')->id('initial-amount') !!}
                                        <div class="form-group" style="margin-bottom: 5px">
                                            <label class="col-md-5" for="amount" style="margin-top: 15px;font-size: 11px;">Penalty Amount</label>
                                            <div class="col-md-7">
                                                <input type="text" name="amount" id="amount" class="form-control" disabled="">
                                            </div>
                                        </div>
                                        <div class="form-group div_added_penalty" style="margin-bottom: 5px;">
                                            <label class="col-md-5" for="added_penalty" id="lbl_added_penalty" style="margin-top: 15px;font-size: 11px;">Added Penalty</label>
                                            <div class="col-md-7">
                                                <input type="number" name="added_penalty" id="added_penalty" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <div class="col-md-offset-5 col-md-7">
                                                <div class="radio">
                                                    <label class="radio-inline" style="font-size:11px">
                                                        <input type="radio" name="is_damaged" value="With Damage">With Damage
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 10px;">
                                            <div class="col-md-offset-5 col-md-7">
                                                <div class="radio">
                                                    <label class="radio-inline" style="font-size:11px">
                                                        <input type="radio" name="is_damaged" value="Without Damage">Without Damage
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    {{--</div>--}}
                                    {{--<div class="col-md-6">--}}
                                        {{--<h5>Total Amount: <span id="total-amount"></span></h5>--}}
                                        <div class="form-group" style="margin-bottom: 5px">
                                            <label class="col-md-5 control-label" for="total-amount" style="margin-top: 10px;font-size: 11px;">Total</label>
                                            <div class="col-md-7">
                                                <input type="text" name="total-amount" id="total-amount" class="form-control" placeholder="0.00" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 5px">
                                            <label class="col-md-5" for="given-amount" style="margin-top: 15px;font-size: 11px;">Amount Received</label>
                                            <div class="col-md-7">
                                                <input type="text" name="given-amount" id="given-amount" class="form-control" placeholder="0.00" required>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 10px">
                                            <label class="col-md-5 control-label" for="change" style="margin-top: 10px;font-size: 11px;">Change</label>
                                            <div class="col-md-7">
                                                <input type="text" name="change" id="change" class="form-control" placeholder="0.00" readonly="readonly">
                                            </div>
                                        </div>
                                        {{--<h5>Change: <span id="change"></span></h5>--}}
                                        {{--{!! BootForm::text('Change', 'change')->disable() !!}--}}
                                         <div class="form-group">
                                             <div class="col-md-offset-5 col-md-7">
                                                 <button type="button" class="btn btn-default btn btn-success form-control" id="process">
                                                     Process Payment
                                                 </button>
                                             </div>
                                         </div>
                                        {!! BootForm::close() !!}
                                    {{--</div>--}}
                                {{--</div>--}}

                            </div>
                        </div>
                    </div>
                </div>

                {{--Lost Book Modal--}}
                <div id="lost-book-modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">LOST BOOK PAYMENT</h5>
                            </div>
                            <div class="modal-body">
                                <p></p>
                                {{--<div class="row">--}}
                                {{--<div class="col-md-6">--}}

                                <div id="lostAlertArea">

                                </div>
                                {{--<div class="alert alert-danger">--}}
                                {{--<strong>Error!</strong> <span id="errorMsg"></span>--}}
                                {{--</div>--}}
                                {!! BootForm::openHorizontal(['md' => [3, 9]])->id('lost-book-payment') !!}
                                {!! BootForm::hidden('lost-trans-id') !!}
                                <div class="form-group" style="margin-bottom: 5px">
                                    <label class="col-md-5" for="lost-amount" style="margin-top: 15px;font-size: 11px;">Processing Fee</label>
                                    <div class="col-md-7">
                                        <input type="text" name="lost-amount" id="lost-amount" class="form-control" value="100" disabled="">
                                    </div>
                                </div>
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                {{--<h5>Total Amount: <span id="total-amount"></span></h5>--}}
                                <div class="form-group" style="margin-bottom: 5px">
                                    <label class="col-md-5 control-label" for="total-lost-amount" style="margin-top: 10px;font-size: 11px;">Total</label>
                                    <div class="col-md-7">
                                        <input type="text" name="total-lost-amount" id="total-lost-amount" class="form-control" placeholder="0.00" value="100" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 5px">
                                    <label class="col-md-5" for="lost-given-amount" style="margin-top: 15px;font-size: 11px;">Amount Received</label>
                                    <div class="col-md-7">
                                        <input type="text" name="lost-given-amount" id="lost-given-amount" class="form-control" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px">
                                    <label class="col-md-5 control-label" for="lost-change" style="margin-top: 10px;font-size: 11px;">Change</label>
                                    <div class="col-md-7">
                                        <input type="text" name="lost-change" id="lost-change" class="form-control" placeholder="0.00" readonly="readonly">
                                    </div>
                                </div>
                                {{--<h5>Change: <span id="change"></span></h5>--}}
                                {{--{!! BootForm::text('Change', 'change')->disable() !!}--}}
                                <div class="form-group">
                                    <div class="col-md-offset-5 col-md-7">
                                        <button type="button" class="btn btn-default btn btn-success form-control is_lost_book" id="process-lost-book">
                                            Process Payment
                                        </button>
                                    </div>
                                </div>
                                {!! BootForm::close() !!}
                                {{--</div>--}}
                                {{--</div>--}}

                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Search Panel</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/transaction/filter') !!}
                    {!! BootForm::bind($filters) !!}
                    @if (Auth::user()->hasRole('admin'))
                        {!! BootForm::text('Barcode', 'barcode') !!}
                        {!! BootForm::select('Users', 'user_id', ['' => '-- Select One --'] + $users)->style('width:100%') !!}
                        {{--{!! BootForm::text('Users', 'username') !!}--}}
                    @endif
{{--                    {!! BootForm::text('Borrowed/Reserved Book Title', 'book_id') !!}--}}
                    {!! BootForm::select('Borrowed/Reserved Book', 'book_id', ['' => '-- Select One --'] + $book_list)->style('width:100%')->data('tags', true) !!}
                    {!! BootForm::select('Filter By Date', 'filter_by_date', ['reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At'])->style('width:100%') !!}
                    {!! BootForm::date('From', 'from') !!}
                    {!! BootForm::date('To', 'to') !!}
                    {!! BootForm::select('Status', 'status',[
                            ''         => '-- Select One --',
                            'reserved' => 'Reserved',
                            'returned' => 'Returned',
                            'canceled' => 'Canceled',
                            'borrowed' => 'Borrowed',
                            'rejected' => 'Rejected',
                            'expired'  => 'Expired'])->style('width:100%') !!}
                    {!! BootForm::select('Is Lost Book', 'is_lost', ['2' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Is Expired Reservation', 'is_expired', ['2' => '', '1' => 'True', '0' => 'False']) ->style('width:100%')!!}
                    {!! BootForm::select('Is Overdue', 'is_overdue', ['2' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'book_id' => 'Borrowed/Reserved Book', 'reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At', 'return_at' => 'Return At'])->style('width:100%') !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending'])->style('width:100%') !!}
                    {!! BootForm::submit('Search')->id('search')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <br>

                <div class="col-sm-9">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <tr class="search-panel">
                                <th>Borrowed/Reserved Books</th>
                                <th>Status</th>
                                <th>Return At</th>
                                <th>Reserved At</th>
                                <th>Borrowed At</th>
                                <th>Returned At</th>
                                <th>Borrower</th>
                                {{--<th>Is Lost</th>--}}
                                {{--<th>Is Expired Reservation</th>--}}
                                {{--<th>Is Overdue</th>--}}
                                {{--<th>Is Damaged</th>--}}
                                {{--<th>Is Rejected</th>--}}
                                <th></th>

                            </tr>

                            @forelse($transactions as $transaction)
                                @if (@$transaction->book->archive != 'Yes')
                                <?php
                                    $table_color = '';

                                    if($transaction->status == 'borrowed') {
                                        $table_color = 'primary';
                                    } elseif($transaction->status == 'expired') {
                                        $table_color = 'warning';
                                    } elseif($transaction->status == 'returned') {
                                        $table_color = 'success';
                                    } elseif($transaction->status == 'canceled' || $transaction->status == 'rejected') {
                                        $table_color = 'danger';
                                    } elseif ($transaction->status == 'lost_and_replaced') {
                                        $table_color = 'info';
                                    }

//                                    var_dump($transaction->toArray());
//                                    var_dump(!empty($transaction->fees) ? $transaction->fees[0]->amount :
//                                            '');
//                                    var_dump(isset($transaction->fees[0]->amount) ? $transaction->fees[0]->amount : '');
                                ?>



                                <tr class="{{ $table_color }}">
                                    {{--<td>{{ @$transaction->fees->amount }}</td>--}}
                                    <td>{{$transaction->book->title}}</td>
                                    <td>{{ ucwords($transaction->status) }}</td>
                                    <td>{{ $transaction->return_at }}</td>
                                    <td>{{@$transaction->reserved_at}}</td>
                                    <td>{{@$transaction->borrowed_at}}</td>
                                    <td>{{@$transaction->returned_at}}</td>
                                    <td>{{@$transaction->user->name }}</td>

                                    {{--<td>--}}
                                        {{--{!! $transaction->is_lost ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>'!!}--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{!! $transaction->is_expired ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{!! $transaction->is_overdue ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{!! $transaction->is_damaged ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{!! $transaction->is_rejected ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}--}}
                                    {{--</td>--}}
                                    <td>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        @if($transaction->status == 'reserved')
                                            @if (Auth::user()->hasRole('admin'))
                                                    <a href="/admin/approve-reservation/{{$transaction->id}}"
                                                       class="btn btn-primary btn-xs btn-block approve-reservation">Approve</a>
                                            @endif
                                            @if (Auth::user()->hasRole('admin'))
                                                <a href="/admin/reject-reservation/{{$transaction->id}}"
                                                   class="btn btn-info btn-xs btn-block
                                                       reject-reservation">Reject</a>

                                            @else
                                                <a href="/admin/reject-reservation/{{$transaction->id}}"
                                                   class="btn btn-info btn-xs btn-block
                                                       reject-reservation">Cancel</a>
                                            @endif

                                        @endif

                                         @if (Auth::user()->hasRole('admin'))
                                            @if (!is_null($transaction->borrowed_at) && $transaction->status != 'returned')
                                                @if ($transaction->status != 'lost_and_replaced')

                                                    @if ($transaction->is_overdue == false)
                                                      <a href="/admin/return-books-exact/{{$transaction->id}}" class="btn btn-warning btn-xs btn-block return-book">Return</a>
                                                    @else
                                                      <a class="btn btn-warning btn-xs btn-block" data-toggle="modal"
                                                           data-target="#return-book" data-trans-id="{{ $transaction->id }} "
                                                           data-trans-amount="{{ !empty($transaction->fees) ? $transaction->fees : '' }} "
                                                           data-hidden-amount="{{ !empty($transaction->fees) ? $transaction->fees : '' }} "
                                                           data-total-amount="{{ !empty($transaction->fees) ? $transaction->fees : '' }} "
                                                      >Return</a>
                                                    @endif
                                                    <a data-toggle="modal"
                                                       data-target="#lost-book-modal" data-lost-trans-id="{{ $transaction->id }} "
                                                            {{--href="/admin/lost-book/{{$transaction->id}}"--}}
                                                       class="btn btn-danger btn-xs btn-block">Lost</a>
                                                @endif

                                            @endif
                                        @endif
                                        {{--<a class="btn btn-success btn-xs btn-block" data-toggle="modal"--}}
                                                {{--data-target="#view-details">Details</a>--}}

                                        <a class="btn btn-success btn-xs btn-block" data-toggle="modal"
                                           data-target="#view-details" data-books="{{ $transaction }}">Details</a>

                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <th colspan="9" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                    <div class="text-center">
                        {!! $transactions->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

@endsection
@section('page_js')
    <script>
        $(function (){
            @if (!Auth::user()->hasRole('admin'))
//                window.location.reload()
            @else
            @endif

            $("#barcode").focus();

            $("#book_id").select2({
                placeholder: "-- Select Book Title --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/transaction_books/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    }
                }
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

            $('.approve-reservation').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to approve this reservation?",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function () {
                    $.post(url, {_token: $('input[name=_token]').val()}, function (data) {
                        swal({
                            title: "Success!",
                            text: "Reservation successfully approved, must be returned at " + data.return_at,
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        window.setTimeout(function(){
                            window.location.href = "/admin/transaction";
                        }, 3000);
                    });
                });
            });

            $('.return-book').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to return this books?",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: 'Yes'
                }, function(){
                    $.post(url, {_token: $('input[name=_token]').val()}, function () {
                        swal({
                            title: "Success!",
                            text: "The book is successfully returned",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

////
                        window.setTimeout(function(){
                            window.location.href = "/admin/transaction";
                        }, 3000);
                    });
                });
            });

//            $(".is_lost_book").on('click', function() {
//                event.preventDefault();
//                var url = $(this).attr('href');
//
//                swal({
//                    title: "Are you sure that this book was lost?",
//                    text: "If Yes, please make sure that the borrower paid the ₱100 processing fee",
//                    showCancelButton: true,
//                    closeOnConfirm: true,
//                    cancelButtonText: "No",
//                    confirmButtonColor: "#5cb85c",
//                    confirmButtonText: 'Yes'
//                }, function(){
//                    console.log(url);
//                    $.post(url, {_token: $('input[name=_token]').val()}, function () {

//                        swal({
//                            title: "Success!",
//                            text: "The book was tagged as lost",
//                            type: "success",
//                            showConfirmButton: false,
//                            timer: 3000
//                        });

//                        window.setTimeout(function(){
//                            window.location.href = "/admin/transaction";
//                        }, 3000);
//                    });
//                });
//            });

            $('#return-book').on('show.bs.modal', function(e) {
                var transId = $(e.relatedTarget).data('trans-id');
                var amount = $(e.relatedTarget).data('trans-amount');
                var hidden_amount = $(e.relatedTarget).data('hidden-amount');
                var total_amount = $(e.relatedTarget).data('total-amount');
//
                var arr = jQuery.parseJSON(amount);
                var hidden_amt_arr = jQuery.parseJSON(hidden_amount);
                var total_amt_arr = jQuery.parseJSON(total_amount);
//
                $(e.currentTarget).find('input[name="trans-id"]').val(transId);
                $(e.currentTarget).find('input[name="amount"]').val(arr[0].amount);
                $(e.currentTarget).find('input[name="initial-amount"]').val(hidden_amt_arr[0].amount);
                $(e.currentTarget).find('input[name="total-amount"]').val(total_amt_arr[0].amount);
//
                $('input[type=radio][name=is_damaged][value="Without Damage"]').prop('checked', true);
                $('.div_added_penalty').hide();
            });

            $('#lost-book-modal').on('show.bs.modal', function(e) {
                var transId = $(e.relatedTarget).data('lost-trans-id');

                $(e.currentTarget).find('input[name="lost-trans-id"]').val(transId);
            });

            $('#process').on('click', function(e) {
                var url = '/admin/return-books/' + $('input[name="trans-id"]').val();

                swal({
                    title: "Are you sure with the amount inputted?",
                    text: "If not, kindly check it first!",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function() {
                    var form_data = $("#process-payment").serialize();

                    if ($('#given-amount').val() == '' || parseInt($('#change').val()) < 0) {
                        $.get(url, form_data, function(data) {
                            var message = '';
                            $.each( data.message, function( key, value ) {
                                var count = parseInt(key) + 1;
                                message += '#' + count + ' : ' + value + '<br>';
                            });
                            $('#alertPopUp').remove();

                            $("#alertArea").append(
                                '<div class="alert alert-danger" id="alertPopUp">' +
                                    '<strong>' + 'ERROR/S' + '</strong>' +
                                    '<h6>' + message + '</h6>' +
                                '</div>'
                            );
//                            console.log(data.message);
                        });
                    } else {
                        $.post(url, form_data, function (data) {
                            console.log(form_data);
                            console.log(
                                "/printouts/receipt/" +
                                $('input[name="trans-id"]').val().trim() + "?added_penalty=" +
                                $('input[name="added_penalty"]').val().trim() + "&is_damaged=" +
                                $('input[name="is_damaged"]').val().trim() + "&total-amount=" +
                                $('input[name="total-amount"]').val().trim() + "&given-amount=" +
                                $('input[name="given-amount"]').val().trim() + "&change=" +
                                $('input[name="change"]').val().trim()
                            );
                            if (data.success) {
                                swal({
                                    title: "Processing payment!",
                                    text: "Please wait while the system is processing the payment",
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                window.setTimeout(function () {
                                    window.location.href = "/printouts/receipt/" +
                                        $('input[name="trans-id"]').val().trim() + "?added_penalty=" +
                                        $('input[name="added_penalty"]').val().trim() + "&is_damaged=" +
                                        $('input[name="is_damaged"]').val().trim() + "&total-amount=" +
                                        $('input[name="total-amount"]').val().trim() + "&given-amount=" +
                                        $('input[name="given-amount"]').val().trim() + "&change=" +
                                        $('input[name="change"]').val().trim()
                                }, 3000);
                            }
                        });
                    }
                });
            });

            $('.reject-reservation').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to cancel/reject this reservation?",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function () {
                    $.post(url, {_token: $('input[name=_token]').val()}, function () {
                        swal({
                            title: "Success!",
                            text: "Reservation successfully canceled/rejected",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        window.setTimeout(function(){
                            window.location.href = "/admin/transaction";
                        }, 3000);
                    });
                });
            });
        });

        $('#view-details').on('show.bs.modal', function(e) {
            var books = $(e.relatedTarget).data('books');


            $('.trans-details').remove();

            var auths = '';
            $.each( books.authors, function( key, value ) {
                auths += value.name + ', ';
            });

            var subjects = '';
            $.each( books.subjects, function( key, value ) {
                subjects += value.name + ', ';
            });

            var is_lost = '';
            if (books.is_lost == true) {
                is_lost = '<span class="label label-success">Yes</span>';
            } else {
                is_lost = '<span class="label label-default">No</span>';
            }

            var is_expired = '';
            if (books.is_expired == true) {
                is_expired = '<span class="label label-success">Yes</span>';
            } else {
                is_expired = '<span class="label label-default">No</span>';
            }

            var is_overdue = '';
            if (books.is_overdue == true) {
                is_overdue = '<span class="label label-success">Yes</span>';
            } else {
                is_overdue = '<span class="label label-default">No</span>';
            }

            var fees = '';
            var paid = '';
            var overdue_days_count = '';
            $.each( books.fees, function( key, value ) {
                fees += value.amount;
                paid += value.is_paid;
                overdue_days_count += value.overdue_day_counts
            });

            var is_paid = '';
            if (paid == true) {
                is_paid = '<span class="label label-success">Yes</span>';
            } else {
                is_paid = '<span class="label label-default">No</span>';
            }

            var is_fiction = '';
            if (books.is_fiction == true) {
                is_fiction = 'Overdue Week/s Count: ';
            } else {
                is_fiction = 'Overdue Day/s Count: ';
            }



            $("#modal-details").append(
                '<div class="trans-details">' +
                    '<div class="panel panel-success">' +
                        '<div class="panel-heading"><span style="font-size: 14px; font-weight: bold">BORROWER INFORMATION </span></div>'+
                            '<div class="panel-body" id="book-info">'
                                + '<table class="table table-bordered table-condensed">'
                                    + '<tr>'  +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">User ID</td>' +
                                        '<td>' + books.user.user_id + '</td>' +
                                      '</tr>' +
                                      '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Name</td>' +
                                        '<td>' + books.user.name + '</td>' +
                                      '</tr>' +
                                      '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Email Address</td>' +
                                        '<td>' + books.user.email + '</td>' +
                                      '</tr>' +
                                '</table>'
//                                + '<span style="color: #5cb85c; font-weight: bold">User ID: </span>' + books.user.user_id + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Name: </span>' + books.user.name + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Email Address: </span>' + books.user.email
                            + '</div>'
                    + '</div>' +
                    '<div class="panel panel-success">' +
                        '<div class="panel-heading"><span style="font-size: 14px; font-weight: bold">BOOK INFORMATION </span></div>'+
                            '<div class="panel-body" id="book-info">'
                                + '<table class="table table-bordered table-condensed">'
                                    + '<tr>'  +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Book Title</td>' +
                                        '<td>' + books.book.title + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Authors</td>' +
                                        '<td>' + auths + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Subjects</td>' +
                                        '<td>' + subjects + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Material</td>' +
                                        '<td>' + books.material_name + '</td>' +
                                    '</tr>' +
                                '</table>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Book Title: </span>' + books.book.title + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Authors: </span>' + auths + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Subjects: </span>' + subjects + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Material: </span>' + books.material_name
                        + '</div>'
                    + '</div>' +
                    '<div class="panel panel-success">' +
                        '<div class="panel-heading"><span style="font-size: 14px; font-weight: bold">TRANSACTION INFORMATION </span></div>'+
                            '<div class="panel-body" id="book-info">'
                                + '<table class="table table-bordered table-condensed">'
                                    + '<tr>'  +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Status</td>' +
                                        '<td>' + books.status + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Reserved At</td>' +
                                        '<td>' + books.reserved_at + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold; width:40%">Borrowed At</td>' +
                                        '<td>' + books.borrowed_at + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Returned At</td>' +
                                        '<td>' + books.returned_at + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Return At</td>' +
                                        '<td>' + books.return_at + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Expired At</td>' +
                                        '<td>' + books.expired_at + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Is Lost</td>' +
                                        '<td>' + is_lost + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Is Expired</td>' +
                                        '<td>' + is_expired + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Is Overdue</td>' +
                                        '<td>' + is_overdue + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Amount</td>' +
                                        '<td>' + fees + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">Is Paid</td>' +
                                        '<td>' + is_paid + '</td>' +
                                    '</tr>' +
                                    '<tr>' +
                                        '<td style="color: #5cb85c; font-weight: bold">' + is_fiction + '</td>' +
                                        '<td>' + overdue_days_count + '</td>' +
                                    '</tr>' +
                                '</table>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Status: </span>' + books.status + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Reserved At: </span>' + books.reserved_at + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Borrowed At: </span>' + books.borrowed_at + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Returned At: </span>' + books.returned_at + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Expired At: </span>' + books.expired_at + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Is Lost: </span>' + is_lost + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Is Expired: </span>' + is_expired + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Is Overdue: </span>' + is_overdue + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Amount: </span>' + fees + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">Is Paid: </span>' + is_paid + '<br>'
//                                + '<span style="color: #5cb85c; font-weight: bold">' + is_fiction + '</span>' + overdue_days_count + '<br>'
                            + '</div>'
                    + '</div>'
                + '</div>');
        });

        $('#amount').prop('disabled',true);
        $('.div_added_penalty').hide();

        $('input[type=radio][name=is_damaged]').change(function(e) {
            if (this.value == 'With Damage') {
                $('.div_added_penalty').show();
                $('#added_penalty').val("");
            }
            else if (this.value == 'Without Damage') {
                $('.div_added_penalty').hide();

                $('#given-amount').val('');
                $("#total-amount").val($('#amount').val());
                $("#change").val('');

            }
        });

        $('#added_penalty').on('input',function(e) {
            var amt = parseInt($('#amount').val());
            var added_amt = parseInt($("#added_penalty").val());
            var total_amt = amt + added_amt;

            if (!isNaN(added_amt)) {
                $('#total-amount').val(total_amt);
            } else {
                $('#total-amount').val($('#amount').val());
            }

        });

        $('#given-amount').on('input',function(e) {
            var amt = parseInt($('#amount').val());
            var added_amt = parseInt($('#added_penalty').val());
            var given_amt = parseInt($("#given-amount").val());

            var total_amt;

            if (!isNaN(added_amt)) {
                total_amt = given_amt - (amt + added_amt);
            } else {
                total_amt = given_amt - amt;
            }

            if (total_amt < 0) {
                $("#change").val(0);
            } else {
                if (!isNaN(given_amt)) {
                    $('#change').val(total_amt);
                } else {
                    $('#change').val('');
                }
            }


        });

        $('#lost-given-amount').on('input',function(e) {
            var amt = parseInt($('#lost-amount').val());
            var given_amt = parseInt($("#lost-given-amount").val());

            var total_amt;

            total_amt = given_amt - amt;

            if (total_amt < 0) {
                $("#process-lost-book").prop('disabled', true);
                $("#lost-change").val(0);
            } else {
                $("#process-lost-book").prop('disabled', false);
                if (!isNaN(given_amt)) {
                    $('#lost-change').val(total_amt);
                } else {
                    $('#lost-change').val('');
                }
            }

        });

        $("#process-lost-book").on('click', function(e) {
            var url = '/admin/lost-book/' + $('input[name="lost-trans-id"]').val();

            console.log(url);
            swal({
                title: "Are you sure that this book was lost?",
                text: "If Yes, please make sure that the borrower paid the ₱100 processing fee and that it was replaced",
                showCancelButton: true,
                cancelButtonText: "No",
                confirmButtonColor: "#5cb85c",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function() {
                var form_data = $("#lost-book-payment").serialize();
                console.log(form_data);

//                if ($('#lost-given-amount').val() == '' || parseInt($('#lost-change').val()) < 0) {
//
//                    console.log('test');
//                    $('#lostAlertPopUp').remove();
//
//                    $("#lostAlertArea").append(
//                        '<div class="alert alert-danger" id="lostAlertPopUp">' +
//                        '<strong>' + 'ERROR' + '</strong>' +
//                        '<h6>' + 'An error occured, please check your input' + '</h6>' +
//                        '</div>'
//                    );
//                } else {
                    $.post(url, form_data, function (data) {
                        console.log(data);
                        console.log(form_data);
                        console.log(
                            "/printouts/lost-book-receipt/" +
                            $('input[name="lost-trans-id"]').val().trim() + "?total-amount=" +
                            $('input[name="lost-amount"]').val().trim() + "&given-amount=" +
                            $('input[name="lost-given-amount"]').val().trim() + "&change=" +
                            $('input[name="lost-change"]').val().trim()
                        );
                        if (data.success) {
                            swal({
                                title: "Processing payment!",
                                text: "Please wait while the system is processing the payment",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            window.setTimeout(function () {
                                window.location.href = "/printouts/lost-book-receipt/" +
                                    $('input[name="lost-trans-id"]').val().trim() + "?total-amount=" +
                                    $('input[name="lost-amount"]').val().trim() + "&given-amount=" +
                                    $('input[name="lost-given-amount"]').val().trim() + "&change=" +
                                    $('input[name="lost-change"]').val().trim()
                            }, 3000);
                        }
                    });
//                }
            });
        });

    </script>
@endsection