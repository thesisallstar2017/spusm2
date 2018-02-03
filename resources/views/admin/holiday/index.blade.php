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

        .table tbody tr.due {
            background-color: #ef6b6b !important;
        }

        .labelClass {
            vertical-align: middle !important;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            border: none !important;
        }

        .outside {border:none !important; vertical-align: middle !important;  }

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
            <div class="col-sm-10 col-sm-offset-10">
                <a href="{{ route('admin.holiday.create') }}" class="btn btn-success"><i class="fa fa-1x fa-plus-circle"></i> Add Event</a>
            </div>
            <div class="col-sm-12 self-class">
                <hr>

                <div >
                    <div class="level">
                        <span class="flex"></span>
                        <small>
                            <table class="table table-responsive" style="margin-bottom:0">
                                <tr>
                                    <th class="outside text-success">Legend:</th>
                                    <td class="labelClass" style="background-color: #ef6b6b">Behind Today (For Deletion)</td>
                                </tr>
                            </table>
                        </small>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover table-condensed table-bordered">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>&nbsp</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($holidays as $holiday)
                            <?php
                                $table_color = '';

                                if (\Carbon\Carbon::now()->format('Y-m-d') > $holiday->event_date->format('Y-m-d')) {
                                    $table_color = 'due';
                                }
                            ?>
                            <tr class="{{ $table_color }}">
                                <td>{{$holiday->name}}</td>
                                <td>{{$holiday->event_date->format('Y-m-d')}}</td>
                                <td style="width: 10%">
                                    {!! BootForm::open()->delete()->action(route('admin.holiday.destroy', ['holiday' => $holiday->id])) !!}
                                    <a class="btn btn-success btn-xs btn-block" href="{{ route('admin.holiday.edit', ['holiday' => $holiday->id]) }}">Edit</a>
                                    {!! BootForm::submit('Delete')->class('btn btn-danger btn-xs btn-block') !!}
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
    </div>
@endsection

@section('page_js')
    <script type="text/javascript">
    </script>
@endsection