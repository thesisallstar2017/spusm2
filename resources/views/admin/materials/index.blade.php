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
                    <h4>MATERIALS</h4>
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


            <div class="col-sm-10 col-sm-offset-10">
                <a href="{{url('admin/materials/create')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Add New Material</a>
            </div>
            <div class="col-sm-12 self-class">

                <hr>

                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Search Panel</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/materials/filter') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::text('Name', 'name') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'name' => 'Name'])->style('width:100%') !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending'])->style('width:100%') !!}
                    {!! BootForm::submit('Search')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <br>

                <div class="col-sm-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr class="search-panel">
                                <th>ID</th>
                                <th>Name</th>
                                <th></th>

                            </tr>
                            @forelse($materials as $material)
                                <tr>
                                    <td><a href="{{url('admin/materials/' . $material->id) . '/edit'}}" class="btn btn-success btn-xs">{{$material->id}}</a></td>
                                    <td>{{$material->name}}</td>
                                    <td>
                                        {!! BootForm::open()->delete()->action(url('admin/materials/' . $material->id)) !!}
                                        <a href="{{url('admin/materials/' . $material->id) . '/edit'}}" role="button" class="btn btn-success btn-xs">Edit</a>
                                        {!! BootForm::submit('Delete')->class('btn btn-danger btn-xs') !!}
                                        {!! BootForm::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="5" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                    <div class="text-center">
                        {!! $materials->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

@endsection