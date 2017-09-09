@extends('layout-clean')

@section('internal_stylesheet')
    <style type="text/css">
        html { margin: 40px 10px !important; }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px 3px 5px 3px;
        }
    </style>
@endsection

@section('content')
    <div style="text-align: center">
        <h3 style="color:#5cb85c;padding: 0px;margin: 0px;"> SAINT PAUL UNIVERSITY SAN MIGUEL BULACAN</h3>
        <h5 style="white-space: pre-line; padding: 0px;margin: 0px;">Address: Cagayan Valley Rd, San Miguel, Bulacan
            Phone: (044) 764 0149
        </h5>
        <br>
        <h3 style="color:#5cb85c; padding: 0px;margin: 0px;">TRANSACTION REPORT</h3>
    </div>
    <div class="book-list" style="font-size: 10px;">
        <table class="table">
            <thead>
                <tr>
                    <th >Username</th>
                    <th >Book Title</th>
                    <th >Is Rejected</th>
                    <th >Is Expired</th>
                    <th >Is Damaged</th>
                    <th >Is Overdue</th>
                    <th >Is Lost</th>
                    <th >Penalty Amount</th>
                    <th >Overdue Day Count</th>
                    <th >Is Paid</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $value)
                <tr style="vertical-align: top">
                    <td style="width: 45px">{{ $value->user->name }}</td>
                    <td style="width: 45px">{{ $value->book->title }}</td>
                    <td style="width: 45px">{{ $value->is_damaged == true ? 'Yes' : 'No' }}</td>
                    <td style="width: 45px">{{ $value->is_rejected == true ? 'Yes' : 'No' }}</td>
                    <td style="width: 45px">{{ $value->is_expired == true ? 'Yes' : 'No' }}</td>
                    <td style="width: 45px">{{ $value->is_overdue == true ? 'Yes' : 'No' }}</td>
                    <td style="width: 45px">{{ $value->is_lost == true ? 'Yes' : 'No' }}</td>
                    <td style="width: 45px">{{ @$value->fees[0]->amount }}</td>
                    <td style="width: 45px"><span style="text-align: center">{{ @$value->fees[0]->overdue_day_counts == 0 ? 1 : $value->fees[0]->overdue_day_counts }}</span></td>
                    <td style="width: 45px">{{ @$value->fees[0]->is_paid == true ? 'Yes' : 'No'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection