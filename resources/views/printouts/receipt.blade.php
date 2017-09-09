@extends('layout-clean')

@section('internal_stylesheet')
    <style type="text/css">
        @media print
        {
            .non-printable { display: none; }
            .printable { display: block; }
        }
    </style>
@stop

@section('content')
    <div class="non-printable">
        <div style="margin:10px 0;" class="row">
            <div class="col-md-12">
                <div class="text-center col-md-12">
                    <a href="/admin/transaction" class="btn btn-lg pull-left" style="background-color: #5cb85c; color:white;">Back</a>
                    <a href="#" class="printBtn btn btn-lg" style="background-color: #5cb85c; color:white;">Print Receipt</a>
                </div>
            </div>
        </div>
        <hr>
    </div>

    <div class="printable">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div>
                    <h3 class="text-center" style="color:#5cb85c"> SAINT PAUL UNIVERSITY SAN MIGUEL BULACAN</h3>
                    <h5 class="text-center" style="white-space: pre-line">Address: Cagayan Valley Rd, San Miguel, Bulacan
                        Phone: (044) 764 0149
                    </h5>
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <h6 class="pull-right"><strong>Date:</strong> <?php echo date('M j, Y')  ?></h6>
                    <br>
                    <h6>
                        <?php echo "<strong>Receipt No:</strong> " . $transaction->fees[0]->receipt_no . "<br>" .
                            "<strong>User ID: </strong> ". $transaction->user->user_id . "<br>" .
                            "<strong>Borrower:</strong> " . $transaction->user->name
                        ?>
                    </h6>

                    <small>
                        <table class="table table-condensed">
                            <tr>
                                <th>Book Title</th>
                                <td style="text-align: right">{{ $transaction->book->title }}</td>
                            </tr>
                            <tr>
                                <th>Overdue Days Count</th>
                                <td style="text-align: right">{{ $transaction->fees[0]->overdue_day_counts }}</td>
                            </tr>
                            <tr>
                                <th>With Damage</th>
                                <td style="text-align: right">{{ $is_damaged == 'With Damage' ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Added Penalty</th>
                                <td style="text-align: right">₱ {{ empty($added_penalty) ? '0' : $added_penalty  }}</td>
                            </tr>
                            <tr>
                                <th>Is Paid</th>
                                <td style="text-align: right">{{ is_null($transaction->fees[0]->is_paid) ? 'No, With Balance of ₱ ' . ($total_amount - $given_amount) : 'Yes' }}</td>
                            </tr>
                        </table>
                    </small>
                    <h6 style="text-align: right;"><strong>Total Amount:</strong> ₱ {{$total_amount}}</h6>
                    <h6 style="text-align: right;"><strong>Amount Received:</strong> ₱ {{$given_amount}}</h6>
                    <h6 style="text-align: right;"><strong>Change:</strong> ₱ {{$change}}</h6>

                </div>
            </div>

        </div>
    </div>

    <div style="margin-top:1700px;margin-bottom:50px;" class="non-printable">

    </div>

@stop

@section('page_js')
    <script type="text/javascript">
        $(document).ready(function() {
            // window.print();
            $(".printBtn").on('click', function() {
                window.print();
            });
        });
    </script>
@stop