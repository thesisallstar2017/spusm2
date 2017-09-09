@extends('layout')

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>CHANGE PASSWORD</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 self-class">
                {!! BootForm::openHorizontal(['sm' => [3, 9]]) !!}
                {!! BootForm::password('Current Password', 'current_password') !!}
                {!! BootForm::password('New Password', 'password') !!}
                {!! BootForm::password('Verify Password', 'password_confirmation') !!}
                {!! BootForm::submit('Change Password')->addClass('btn btn-success') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
@stop