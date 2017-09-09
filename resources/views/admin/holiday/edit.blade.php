@extends('layout')

@section('content')
    <h2>Edit Holiday</h2>
    <div id="form-container">
        {!! BootForm::open()->action(route('admin.holiday.update', ['holiday' => $holiday->id]))->patch() !!}
        {!! BootForm::bind($holiday) !!}
        {!! BootForm::text('Name', 'name') !!}
        {!! BootForm::date('Date', 'event_date')->required() !!}
        {!! BootForm::submit('Save')->class('btn btn-primary') !!}
        {!! BootForm::close() !!}
    </div>
@endsection