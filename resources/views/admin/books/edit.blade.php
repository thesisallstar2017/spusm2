@inject('materials', 'App\Models\Material')
{{--@inject('authors', 'App\Models\Author')--}}
{{--@inject('subjects', 'App\Models\Subject')--}}
@inject('books', 'App\Models\Book')


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
                    <h4>Books</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('admin/books')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 self-class">
                <p style="font-size: 25px; text-align: center">Edit <strong style="color:#3c763d">BOOK</strong></p>
                <br>
                <div class="col-md-6">
                    <?php
                    $max_year = 2070;
                    for ($i = 1700; $i <= $max_year; $i++) {
                        $year[$i] = $i;
                    }
                    ?>
                    {!! BootForm::open()->patch()->action(url('admin/books/' . $book['id'])) !!}
                    {!! BootForm::bind($book) !!}
                    {!! BootForm::text('Card Number', 'card_number')->required() !!}
                    {!! BootForm::text('Call Number', 'call_number')->required() !!}
                    {!! BootForm::text('Title', 'title')->required() !!}
                    {!! BootForm::select('Author', 'authors')->options($authors)->id('authors')->required()->data('tags', true)->multiple
                    ()->style('width:100%') !!}
                        {{--{!! var_dump($subjects) !!}--}}
                    {!! BootForm::select('Subject', 'subjects')->options($subjects)->id('subjects')->required()->data('tags', true)->multiple()->style('width:100%') !!}
                    {!! BootForm::select('Material Type', 'material_id', ['' => ''] + $materials->getMaterials())->placeholder('Material Type')->required()->style('width:100%') !!}
                    {!! BootForm::text('Publisher', 'publisher')->required() !!}
                    <div class="form-group">
                        <label class="control-label" for="published_year">Year Published</label>
                        <input type="number" name="published_year" id="published_year" class="form-control">
                    </div>
                    {{--{!! BootForm::select('Year Published', 'published_year', ['' => ''] + $year)->required()->style--}}
                    {{--('width:100%') !!}--}}
                    {!! BootForm::text('Publish Place', 'publish_place')->required() !!}
                    <div class="form-group">
                        <label class="control-label" for="quantity">Copies Available</label>
                        <input type="number" name="quantity" id="quantity" value="{{ $book['quantity'] }}"class="form-control" required="required">
                    </div>
                    {!! BootForm::text('ISBN', 'isbn') !!}
                    {!! BootForm::select('ETAL', 'etal', ['' => '', 'True' => 'True', 'False' => 'False'])->style('width:100%') !!}

                </div>
                <div class="col-md-6">
                    {!! BootForm::text('Edition', 'edition') !!}
                    {!! BootForm::text('Physical Description', 'physical_desc') !!}
                    {!! BootForm::text('AETitle', 'aetitle') !!}
                    {!! BootForm::text('STitle', 'stitle') !!}
                    {!! BootForm::select('Book Level', 'book_level', ['' => '', 'CO' => 'CO', 'HS' => 'HS', 'GS' => 'GS'])->style('width:100%') !!}
                    {!! BootForm::text('Editor', 'editor') !!}
                    {!! BootForm::text('Illustrator', 'illustrator') !!}
                    {!! BootForm::text('Compiler', 'compiler') !!}
                    {!! BootForm::textarea('Note', 'note')->rows(4) !!}
                    {!! BootForm::submit('Save', 'save')->class('btn btn-success form-control') !!}
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection

@section('page_js')
    <script type="text/javascript">
        $(function() {
            $("#material_id").select2({
                placeholder: "-- Select Material Type --",
                allowClear: true
            });

            $("#authors").select2({
                placeholder: "-- Select Authors --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/authors/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        var authors = $.map(data.results, function (obj) {
                            obj.id = obj.text;
                            return obj;
                        });
                        return {
                            results: authors
                        };
                    }
                }
            });

            $("#subjects").select2({
                placeholder: "-- Select Subjects --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/subjects/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        var authors = $.map(data.results, function (obj) {
                            obj.id = obj.text;
                            return obj;
                        });
                        return {
                            results: authors
                        };
                    }
                }
            });

//            $("#published_year").select2({
//                placeholder: "-- Select Year Published --",
//                allowClear: true
//            });

            $("#etal").select2({
                placeholder: "-- Select if true or false --",
                allowClear: true
            });

            $("#book_level").select2({
                placeholder: "-- Select Book Level --",
                allowClear: true
            });
        });
    </script>
@endsection