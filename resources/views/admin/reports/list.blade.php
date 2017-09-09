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

        .btn-xlarge {
            padding: 15px 25px;
            height: 165px;
            border-color: #3c763d;
        }
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>DOWNLOAD REPORT</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-sm-12 self-class">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>File Path</th>
                                {{--<th>From</th>--}}
                                {{--<th>To</th>--}}
                                <th>File Size</th>
                                <th></th>
                            </tr>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{$report->id}}</td>
                                    <td>{{$report->type}}</td>
                                    <td>{{$report->status}}</td>
                                    <td>
                                        @if ($report->filename != '')
                                        <a href="/admin/reports/download/{{$report->id}}" class="btn btn-success btn-xs">
                                            {{$report->filename }}
                                        </a>
                                        @endif
                                    </td>
                                    {{--<td>{{$report->from_date}}</td>--}}
                                    {{--<td>{{$report->to_date}}</td>--}}
                                    <td>{{$report->file_size}}</td>
                                    <td>
                                        <a href="/admin/reports/download/{{$report->id}}" class="btn btn-success btn-xs">Download</a>
                                        <a href="/admin/reports/delete-report/{{$report->id}}" class="btn btn-danger btn-xs">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-danger">No data found</td>
                                </tr>
                            @endforelse

                        </table>

                        <div class="text-center">
                            {!! $reports->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br><br>
@endsection
