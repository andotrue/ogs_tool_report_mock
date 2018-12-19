@extends('common.layout')

@section('css')
@endsection

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> {{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} </h1>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

             <form action="{{ route('reporttesttable.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('parent_user_id')) has-error @endif">
                   <label for="parent_user_id-field">parent_user_id <span class="btn-xs btn-danger">必須</span></label>
                   <input type="text" id="parent_user_id-field" name="parent_user_id" class="form-control" value="{{ old("parent_user_id") }}"/>
                       @if($errors->has("parent_user_id"))
                        <span class="help-block">{{ $errors->first("parent_user_id") }}</span>
                       @endif
                </div>
                    
                <div class="form-group @if($errors->has('child_user_id')) has-error @endif">
                    <label for="child_user_id-field">child_user_id <span class="btn-xs btn-danger">必須</span></label>
                    <input type="text" id="child_user_id-field" name="child_user_id" class="form-control" value="{{ old("child_user_id") }}" />
                       @if($errors->has("child_user_id"))
                        <span class="help-block">{{ $errors->first("child_user_id") }}</span>
                       @endif
                </div>
                    
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <a class="btn btn-link pull-right" href="{{ route('reporttesttable.index') }}"><i class="glyphicon glyphicon-backward"></i> 戻る</a>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
@endsection
