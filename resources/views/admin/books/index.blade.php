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
                    <h4>BOOKS</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="level">
                    <span class="flex">
                        <a href="{{url('admin/materials')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Manage Materials</a>
                    </span>
                    <a href="{{url('admin/books/create')}}" class="btn btn-success" style="margin-right: 5px"><i class="fa fa-1x
                fa-plus-circle"></i> Add New Book</a>
                    <a href="/admin/reports/list/book-list" class="btn btn-primary btn-success">Download Book List</a>

                </div>
            </div>
            <div class="col-sm-12 self-class">

                <div id="view-criteria-of-weeding" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">Weeding</h5>
                            </div>
                            <div class="modal-body">
                                {{--<small>--}}
                                <div id="modal-details">
                                    <h4>Criteria of Weeding</h4>
                                    <ol>
                                        <li>Age & date of publication.</li>
                                        <ol type="a">
                                            <li>Books remove from shelves according to date of publication. , copyright. </li>
                                            <li>Materials no longer in demand & not support the curriculum. </li>
                                            <li>Old edition or no longer use.</li>
                                            <li>Outdated & badly written works.</li>
                                        </ol>
                                        <li>Shelf time period  longer than ten year's. </li>
                                        <li>Last date of circulation</li>
                                        <li>Physical condition if bad, dirty or worn</li>
                                        <li>Language ,changes in teaching program.</li>
                                        <li>Duplicate copies.</li>
                                    </ol>
                                    <h4>Procedures</h4>
                                    <ol>
                                        <li>Remove from the books the book pockets & card.</li>
                                        <li>Stamp the reverse side of the front cover w/ word Discarded & date. </li>
                                        <li>Ownership marks must be eliminated. </li>
                                        <li>Indicate in the accession record the remark DISCARDED & the date. </li>
                                        <li>Shelf list record & catalog cards must be removed like the author, title , subject cards etc.</li>
                                    </ol>
                                    {!! BootForm::open()->id('archive-form') !!}
                                    {!! BootForm::hidden('book-id') !!}
                                    {!! BootForm::textarea('Reason For Weeding', 'reason_for_weeding')->rows(4) !!}
                                    {!! BootForm::close() !!}
                                    <button class="btn btn-danger btn-block" id="archive">Archive this book</button>
                                </div>
                                {{--</small>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Search Panel</h4>
                    <br>
                    <?php
                    $max_year = 2070;
                    for ($i = 1700; $i <= $max_year; $i++) {
                        $year[$i] = $i;
                    }
                    ?>
                    {!! BootForm::open()->action('/admin/books/filter')->id('filters') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::text('Call Number', 'call_number') !!}
                    {!! BootForm::text('Card Number', 'card_number') !!}
                    {!! BootForm::text('Title', 'title') !!}
                    {!! BootForm::text('Publisher', 'publisher') !!}
                    {!! BootForm::select('Year Published', 'published_year', ['' => ''] + $year)->style('width:100%') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'card_number' => 'Card Number', 'call_number' => 'Call Number', 'title' => 'Title', 'publisher' => 'Publisher', 'published_year' => 'Year Published'])->style('width:100%') !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending'])->style('width:100%') !!}
                    {!! BootForm::select('Borrowed/Archived/Received Books', 'filter_by', ['' => '-- Select One --', 'borrowed' => 'Borrowed', 'reserved' => 'Reserved', 'archived' => 'Archived'])->style('width:100%') !!}
                    {!! BootForm::submit('Search')->addClass('btn btn-success') !!}
                </div>

                <div class="col-sm-9">
                    {!! BootForm::button('Generate Book List')->id('download-pdf')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                    <br><br>

                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr class="search-panel">
                                <th style="text-align: center">Barcode</th>
                                <th>Card Number</th>
                                <th>Call Number</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Subject</th>
                                <th>Publisher</th>
                                <th>Year Published</th>
                                <th></th>

                            </tr>
                            @forelse($books as $book)
                                @if ($book->archive != 'Yes' ||
                                (isset($filters['filter_by']) && $filters['filter_by'] == 'archived'))
                                <tr>
                                    <td style="text-align: center;">
                                        <div class="barcode" style="height:60px">{{$book->barcode}}</div>
                                        {{$book->barcode}}
                                    </td>
                                    <td>{{$book->card_number}}</td>
                                    <td>{{$book->call_number}}</td>
                                    <td>{{$book->title}}</td>
                                    <td>
                                    @foreach ($book->authors as $author)
                                        {{$author->name}} <br>
                                    @endforeach
                                    </td>
                                    <td>
                                    @foreach ($book->subjects as $subject)
                                        {{$subject->name}} <br>
                                    @endforeach
                                    </td>
                                    <td>{{$book->publisher}}</td>
                                    <td>{{$book->published_year}}</td>
                                    <td>
                                        @if ($book->archive != 'Yes')
                                        <a href="{{url('admin/books/' . $book->id) . '/edit'}}" role="button" class="btn btn-success btn-xs btn-block">Edit</a>

                                            <a data-toggle="modal"
                                               data-target="#view-criteria-of-weeding" data-book-id="{{ $book->id }} "
                                               {{--href="/admin/lost-book/{{$transaction->id}}"--}}
                                               class="btn btn-danger btn-xs btn-block">For Archiving</a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <th colspan="8" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                    <div class="text-center">

                    {!! $books->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

@endsection

@section('page_js')
    <script src="/js/jquery-barcode.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".barcode").each(function (i, e) {
                $(e).barcode($(e).text(), "code128", { showHRI: false} );
            });
        });


        $("#download-pdf").click(function() {
            var form_data   = $("#filters").serialize();
            $.get("/admin/books/download", form_data, function(data) {
                if (data.success == true) {
                    swal({title: "Success",   text: data.message, type: "success", showConfirmButton: false, timer: 3000});
                }
            });
        });


        $('#view-criteria-of-weeding').on('show.bs.modal', function(e) {
            var bookId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="book-id"]').val(bookId);
        });

        $("#archive").click(function() {

            var url = '/admin/books/' + "{{ isset($book->id) ? $book->id : ''}}" + '/archive';

            console.log(url);
            swal({
                title: "Are you sure to archive this book?",
                showCancelButton: true,
                cancelButtonText: "No",
                confirmButtonColor: "#5cb85c",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function() {

                var form_data = $("#archive-form").serialize();

                $.post(url, form_data, function (data) {

                    if (data.success) {
                        swal({
                            title: "Success!",
                            text: "Book is successfully archived",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        window.setTimeout(function(){
                            window.location.href = "/admin/books";
                        }, 3000);
                    }
                });
//                }
            });
        });



    </script>
@stop