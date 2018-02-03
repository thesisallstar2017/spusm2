@extends('layout')

@section('internal_stylesheet')
    <style type="text/css">
        .self-class {
            font-size: 12px;
            height:100%;
        }
    </style>
@endsection

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>HOLIDAYS/SUSPENSIONS</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('admin/holiday')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="col-md-12 self-class">
            <small>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 self-class">
                        <p style="font-size: 25px; text-align: center">Edit <strong style="color:#3c763d">HOLIDAY</strong></p>
                        <br>
                        {!! BootForm::open()->patch()->action(route('admin.holiday.update', ['holiday' => $holiday->id])) !!}
                        {!! BootForm::bind($holiday) !!}
                        {!! BootForm::text('Name', 'name') !!}
                        {!! BootForm::date('Date', 'event_date') !!}
                        {!! BootForm::submit('Save', 'save')->class('btn btn-success form-control') !!}
                        {!! BootForm::close() !!}
                    </div>
                </div>
            </small>
        </div>
    </div>
@endsection