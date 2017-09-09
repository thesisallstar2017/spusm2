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
                <p style="font-size: 25px; text-align: center">Edit <strong style="color:#3c763d">USER {{ strtoupper($user['name']) }}</strong></p>
                <br>
                {!! BootForm::open()->patch()->action(url('admin/users/' . $user['id'])) !!}
                <input type="hidden" name="id" value="{{$user['id']}}" />
                {!! BootForm::bind($user) !!}
                @if($user['roles'][0] == 1)
                    {!! BootForm::text('Admin ID', 'user_id') !!}
                @elseif ($user['roles'][0] == 2)
                    {!! BootForm::text('Faculty ID', 'user_id') !!}
                @elseif ($user['roles'][0] == 3)
                    {!! BootForm::text('Student ID', 'user_id') !!}
                @endif
                {!! BootForm::text('Name', 'name') !!}
                {!! BootForm::password('Password', 'password') !!}
                {!! BootForm::text('E-mail', 'email') !!}
                {!! BootForm::select('Roles', 'roles')->options($roles)->multiple()->style('width:100%') !!}
                {!! BootForm::submit('Update', 'save')->class('btn btn-success form-control') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection