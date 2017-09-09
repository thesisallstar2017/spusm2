@extends('layout')

@section('content')
    <h2>Holidays</h2>
    <a href="{{ route('admin.holiday.create') }}" class="btn btn-primary">Add holiday</a>
    <table class="table">
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
                <td>{{$holiday->event_date->format('Y-m-d H:i:s')}}</td>
                <td>
                    <a class="btn btn-default" href="{{ route('admin.holiday.edit', ['holiday' => $holiday->id]) }}">Edit</a>
                    {!! BootForm::open()->delete() !!}
                    {!! BootForm::submit('Delete')->class('btn btn-danger') !!}
                    {!! BootForm::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection