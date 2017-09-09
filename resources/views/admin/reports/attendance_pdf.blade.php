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
        <h3 style="color:#5cb85c; padding: 0px;margin: 0px;">Attendance Report</h3>
    </div>
    <div class="book-list" style="font-size: 10px;">
        <table class="table">
            <thead>
            <tr>
                <th style="text-align: center">UserID</th>
                <th style="text-align: center">Username</th>
                <th style="text-align: center">Logged In At</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $value)
                <tr>
                    <td style="text-align:center;width: 255px">{{ $value->user->user_id }}</td>
                    <td style="text-align:center;width: 255px">{{ $value->user->name }}</td>
                    <td style="text-align:center;width: 255px">{{ $value->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection