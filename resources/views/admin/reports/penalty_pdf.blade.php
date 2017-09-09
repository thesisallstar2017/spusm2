@extends('layout-clean')

@section('content')
    <h2 style="text-align: center">Penalty Report</h2>
    <div class="book-list" style="font-size: 10px;">
        <table class="table">
            <thead>
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
            </thead>
            <tbody>
            @foreach($data as $value)
                <tr>
                    <td style="text-align:center;width: 70px">{{ $value->user->name }}</td>
                    <td style="text-align:center;width: 120px">{{ $value->book->title }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_damaged }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_rejected }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_expired }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_damaged }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_overdue }}</td>
                    <td style="text-align:center;width: 40px">{{ $value->is_lost }}</td>
                    <td style="text-align:center;width: 40px">{{ @$value->fees[0]->amount }}</td>
                    <td style="text-align:center;width: 40px">{{ @$value->fees[0]->overdue_day_counts == 0 ? 1 : $value->fees[0]->overdue_day_counts }}</td>
                    <td style="text-align:center;width: 40px">{{ @$value->fees[0]->is_paid }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection