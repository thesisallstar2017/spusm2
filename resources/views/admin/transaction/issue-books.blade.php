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
                    <h4>ISSUE BOOKS</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 self-class">
                {!! BootForm::open() !!}
                {!! BootForm::text('') !!}
                {!! BootForm::close() !!}
            </div>

        </div>
    </div>

@endsection
@section('page_js')
    <script>
        $(function (){

        });

    </script>
@endsection