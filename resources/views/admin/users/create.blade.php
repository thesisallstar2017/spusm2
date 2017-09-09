@extends('layout')

@section('internal_stylesheet')
    <style type="text/css">
        .self-class {
            font-size: 12px;
            height:100%;
        }
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>Users</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('admin/users')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 self-class">
                <p style="font-size: 25px; text-align: center">Add <strong style="color:#3c763d">NEW</strong></p>
                <br>
                {!! BootForm::open()->post()->action(url('admin/users')) !!}
                {!! BootForm::text('Admin/Faculty/Student ID', 'user_id') !!}
                {!! BootForm::text('Name', 'name') !!}
                {!! BootForm::password('Password', 'password') !!}
                {!! BootForm::text('E-mail', 'email') !!}
                {!! BootForm::select('Roles', 'roles')->options($roles)->multiple()->style('width:100%') !!}
                {!! BootForm::submit('Save', 'save')->class('btn btn-success form-control') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection