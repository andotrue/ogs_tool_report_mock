@extends('common.layout')

@section('header')
<div class="page-header">
        <h1>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} #{{$testtable->id}}</h1>
        <form action="{{ route('reporttesttable.destroy', $testtable->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('reporttesttable.edit', $testtable->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <blockquote><label for="name">ID</label></blockquote>
                    <p class="form-control-static">{{$testtable->id}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="title">parent_user_id</label></blockquote>
                     <p class="form-control-static">{{$testtable->parent_user_id}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="email">child_user_id</label></blockquote>
                     <p class="form-control-static">{{$testtable->child_user_id}}</p>
                </div>

            </form>

            <a class="btn btn-link" href="{{ route('reporttesttable.index') }}"><i class="glyphicon glyphicon-backward"></i>  戻る</a>

        </div>
    </div>

@endsection