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
                    <h4>BOOK DETAILS</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('/')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $authors = [];
            foreach ($book->authors as $author) {
                $authors[] = $author->name;
            }

            $subjects = [];
            foreach ($book->subjects as $subject) {
                $subjects[] = $subject->name;
            }

            ?>
                <div id="reserve_book_admin" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">INPUT BORROWER's NAME</h5>
                            </div>
                            <div class="modal-body">
                                <p></p>

                                {!! BootForm::open()->id('reserveBookAdmin') !!}
                                {!! BootForm::select('Users', 'user_id', ['' => '-- Select One --'] + $users)->style('width:100%') !!}
                                {!! BootForm::button('Borrow', 'reserve-admin')->class('btn btn-success form-control reserve-admin') !!}
                                {!! BootForm::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            <div class="col-sm-8 col-sm-offset-2 self-class">
                <p style="font-size: 25px; text-align: center"><strong style="color:#3c763d">{{strtoupper($book->title)}} </strong></p>
                <br>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Material</th>
                        <td>{{ $book->material->name }}</td>
                    </tr>
                    <tr>
                        <th>Card Number</th>
                        <td>{{ $book->card_number }}</td>
                    </tr>
                    <tr>
                        <th>Call Number</th>
                        <td>{{ $book->call_number }}</td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>{{ $book->isbn }}</td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $book->title }}</td>
                    </tr>
                    <tr>
                        <th>Authors</th>
                        <td>{{ implode(', ', $authors) }}</td>
                    </tr>
                    <tr>
                        <th>Subjects</th>
                        <td>{{ implode(', ', $subjects) }}</td>
                    </tr>
                    <tr>
                        <th>Published</th>
                        <td>{{ $book->publisher . ' ' . $book->publish_place . ' ' . $book->published_year }}</td>
                    </tr>
                    <tr>
                        <th>Physical Description</th>
                        <td>{{ $book->physical_desc }}</td>
                    </tr>
                    <tr>
                        <th>Total Copies</th>
                        <td>{{ $book->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Available Copies</th>
                        <td>{{ $book->available_quantity }}</td>
                    </tr>
                </table>
                @if (Auth::check())
                    @if (!Auth::user()->hasRole('admin'))
                        {!! BootForm::open()->id('reserveBook')->post()->action(url('admin/reserve-books/' . $book->id)) !!}
                        {!! BootForm::submit('Reserve', 'reserve')->class('btn btn-success form-control reserve') !!}
                        {!! BootForm::close() !!}
                    @else
                        {!! BootForm::open() !!}

                        <a data-toggle="modal"
                           data-target="#reserve_book_admin"
                           {{--href="/admin/lost-book/{{$transaction->id}}"--}}
                           class="btn btn-success form-control">Borrow</a>
                        {{--{!! BootForm::button('Reserve', 'reserve-admin')->class('btn btn-success form-control reserve-admin') !!}--}}
                        {!! BootForm::close() !!}
                    @endif
                @else
                    <?php session(['current_url' => url()->current()]);?>
                    <a href="/login" class="btn btn-success form-control">LOGIN TO RESERVE THIS BOOK</a>
                @endif
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection


@section('page_js')
    <script type="text/javascript">
        $(function() {
            $('.reserve').prop('disabled', false);
            $("#reserveBook").on('submit', function (e) {
                $('.reserve').attr('disabled', 'disabled');
            });

            $("#user_id").select2({
                placeholder: "-- Select Name --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/username/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    }
                }
            });

            $('.reserve-admin').prop('disabled', false);

            $(".reserve-admin").on('click', function(e) {
                var url = '/admin/reserve-books-admin/' + "{{$book->id}}";
                $('.reserve-admin').attr('disabled', 'disabled');

                    var form_data = $("#reserveBookAdmin").serialize();
                    console.log(form_data);

                    $.post(url, form_data, function (data) {
                        console.log(data);
                        console.log(form_data);

                        if (data.success) {
                            swal({
                                title: data.message,
//                                text: "Please wait while the system is processing the payment",
                                showConfirmButton: true
                            });

                            window.setTimeout(function () {
                            }, 3000);
                            window.setTimeout(function(){
                                window.location.href = "/admin/transaction";
                            }, 3000);
                        } else {
                            swal({
                                title: data.message,
//                                text: "Please wait while the system is processing the payment",
                                showConfirmButton: true
                            });
                            $('.reserve-admin').prop('disabled', false);
                        }
                });
            });
        });
    </script>
@endsection