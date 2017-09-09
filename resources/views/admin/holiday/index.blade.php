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
                    <h4>HOLIDAYS/SUSPENSIONS</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 self-class">
                <a href="{{ route('admin.holiday.create') }}" class="btn btn-success" id="toggle-form">Add holiday</a>
                <br><br>
                <div id="form-container" style="display: none">
                    {!! BootForm::open()->action(route('admin.holiday.store'))->post() !!}
                    {!! BootForm::text('Name', 'name') !!}
                    {!! BootForm::date('Date', 'event_date')->required() !!}
                    {!! BootForm::submit('Save')->class('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <table class="table table-hover table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($holidays as $holiday)
                        <tr>
                            <td>{{$holiday->name}}</td>
                            <td>{{$holiday->event_date->format('Y-m-d')}}</td>
                            <td>
                                <a class="btn btn-default" href="{{ route('admin.holiday.edit', ['holiday' => $holiday->id]) }}">Edit</a>
                                {!! BootForm::open()->delete()->action(route('admin.holiday.destroy', ['holiday' => $holiday->id]))->style('display: inline') !!}
                                {!! BootForm::submit('Delete')->class('btn btn-danger') !!}
                                {!! BootForm::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $holidays->render() !!}
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script type="text/javascript">
        $(function() {
            $("#toggle-form").on('click', function() {
                $("#form-container").toggle();
                return false;
            });
        });
    </script>
@endsection