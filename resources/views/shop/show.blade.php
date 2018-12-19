@extends('tool.common.layout')

@section('header')
<div class="page-header">
        <h1>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}</h1>
        <form action="{{ route('store.destroy', $store->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('store.edit', $store->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                    <blockquote><label for="nome">ID</label></blockquote>
                    <p class="form-control-static">{{$store->id}}</p>
                </div>
                
                <div class="form-group">
                     <blockquote><label for="storename">施設名</label></blockquote>
                     <p class="form-control-static">{{$store->storename}}</p>
                </div>
                
                <div class="form-group">
                     <blockquote><label for="comment">コメント</label></blockquote>
                     <p class="form-control-static">{{$store->comment}}</p>
                </div>
                
                <div class="form-group">
                     <blockquote><label for="comment">画像詳細</label></blockquote>
                     <p class="form-control-static">{{$store->imagedetail}}</p>
                </div>
                
                <div class="form-group">
                     <blockquote><label for="created_tool_user_id">作成管理者ID</label></blockquote>
                     <p class="form-control-static">{{$store->created_tool_user_id}}</p>
                </div>
                
                <div class="form-group">
                     <blockquote><label for="updated_tool_user_id">更新管理者ID</label></blockquote>
                     <p class="form-control-static">{{$store->updated_tool_user_id}}</p>
                </div>
            </form>

            <a class="btn btn-link" href="{{ route('store.index') }}"><i class="glyphicon glyphicon-backward"></i>  戻る</a>

        </div>
    </div>

@endsection