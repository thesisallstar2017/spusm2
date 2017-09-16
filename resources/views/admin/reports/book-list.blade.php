@extends('layout-clean')

@section('internal_stylesheet')
    <style type="text/css">
        html { margin: 40px 10px !important; }
    </style>
@endsection

@section('content')
    <div style="text-align: center">
        <h3 style="color:#5cb85c;padding: 0px;margin: 0px;"> SAINT PAUL UNIVERSITY SAN MIGUEL BULACAN</h3>
        <h5 style="white-space: pre-line; padding: 0px;margin: 0px;">Address: Cagayan Valley Rd, San Miguel, Bulacan
            Phone: (044) 764 0149
        </h5>
        <br>
        <h3 style="color:#5cb85c; padding: 0px;margin: 0px;">BOOK LIST
            @if (isset($request['filter_by']))
                @if ($request['filter_by'] == 'borrowed')
                    (Borrowed)
                @elseif ($request['filter_by'] == 'reserved')
                    (Reserved)
                @elseif ($request['filter_by'] == 'archived')
                    (Archived)
                @endif
            @endif
        </h3>
    </div>
    <div class="book-list" style="font-size: 10px;">
        @if ($request['filter_by'] == 'archived')
            <table class="table">
                <thead>
                <tr>
                    <th style="text-align: center">Barcode</th>
                    <th style="text-align: center">Card Number</th>
                    <th style="text-align: center">Call Number</th>
                    <th style="text-align: center">Title</th>
                    <th style="text-align: center">Author</th>
                    <th style="text-align: center">Subject</th>
                    <th style="text-align: center">Publisher</th>
                    <th style="text-align: center">Year Published</th>
                    <th style="text-align: center">Reason For Weeding</th>
                    <th style="text-align: center">Archived By</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $key => $book)
                    <tr style="vertical-align: top">
                        <td style="width: 144px;">
                            <img style="text-align: left" src="data:image/png;base64,{!! DNS1D::getBarcodePNG($book->barcode, 'C39', 0.75, 33) !!}" />
                            <br>
                            <div style="text-align: center">{{$book->barcode}}</div>
                        </td>
                        <td style="text-align:center;width: 60px">{{$book->card_number}}</td>
                        <td style="text-align:center;width: 60px;">{{$book->call_number}}</td>
                        <td style="text-align:center;width: 60px">{{$book->title}}</td>
                        <td style="text-align:center;width: 60px">
                            @foreach ($book->authors as $author)
                                {{$author->name}} <br>
                            @endforeach
                        </td>
                        <td style="text-align:center;width: 60px">
                            @foreach ($book->subjects as $subject)
                                <span style="text-align: center">{{$subject->name}} </span><br>
                            @endforeach
                        </td>
                        <td style="text-align:center;width: 60px;">{{$book->publisher}}</td>
                        <td style="text-align:center;width: 60px;">{{$book->published_year}}</td>
                        <td style="text-align:center;width: 80px;">{{$book->reason_for_weeding}}</td>
                        <td style="text-align:center;width: 60px;">{{$book->archived_by}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th style="text-align: center">Barcode</th>
                    <th style="text-align: center">Card Number</th>
                    <th style="text-align: center">Call Number</th>
                    <th style="text-align: center">Title</th>
                    <th style="text-align: center">Author</th>
                    <th style="text-align: center">Subject</th>
                    <th style="text-align: center">Publisher</th>
                    <th style="text-align: center">Year Published</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $key => $book)
                    <tr style="vertical-align: top">
                        <td style="width: 144px;">
                            <img style="text-align: left" src="data:image/png;base64,{!! DNS1D::getBarcodePNG($book->barcode, 'C39', 0.75, 33) !!}" />
                            <br>
                            <div style="text-align: center">{{$book->barcode}}</div>
                        </td>
                        <td style="text-align:center;width: 70px">{{$book->card_number}}</td>
                        <td style="text-align:center;width: 70px;">{{$book->call_number}}</td>
                        <td style="text-align:center;width: 100px">{{$book->title}}</td>
                        <td style="text-align:center;width: 80px">
                            @foreach ($book->authors as $author)
                                {{$author->name}} <br>
                            @endforeach
                        </td>
                        <td style="text-align:center;width: 70px">
                            @foreach ($book->subjects as $subject)
                                <span style="text-align: center">{{$subject->name}} </span><br>
                            @endforeach
                        </td>
                        <td style="text-align:center;width: 70px;">{{$book->publisher}}</td>
                        <td style="text-align:center;width: 50px;">{{$book->published_year}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection