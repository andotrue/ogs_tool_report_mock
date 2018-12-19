@extends('tool.common.layout')

@section('header')
<div class="page-header">
        <h1>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} #{{$user->id}}</h1>
        <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-warning btn-group" role="group" href="{{ route('user.edit', $user->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
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
                    <p class="form-control-static">{{$user->id}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="title">TITLE</label></blockquote>
                     <p class="form-control-static">{{$user->name}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="email">EMAIL</label></blockquote>
                     <p class="form-control-static">{{$user->email}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="role">ROLE</label></blockquote>
                     <p class="form-control-static">{{$user->role}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="store_id">施設</label></blockquote>
                     <p class="form-control-static">{{$user->store_id}}</p>
                </div>
                <div class="form-group">
                     <blockquote><label for="shop_name">SHOP_NAME</label></blockquote>
                     <p class="form-control-static">{{$user->shop_name}}</p>
                </div>
                
                <!-- 
                <div class="form-group">
                     <label for=" open_date ">PASSWORD</label>
                     <p class="form-control-static">{{$user->password}}</p>
                </div>
                <div class="form-group">
                     <label for=" close_date ">REMEMBER_TOKEN</label>
                     <p class="form-control-static">{{$user->remember_token}}</p>
                </div>
                 -->
            </form>

            <a class="btn btn-link" href="{{ route('user.index') }}"><i class="glyphicon glyphicon-backward"></i>  戻る</a>

        </div>
    </div>

@endsection