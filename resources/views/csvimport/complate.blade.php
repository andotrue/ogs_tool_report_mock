{{-- @{{extends('layouts.app')}} --}}
@extends('common.layout')

@section('css')
	<link type="text/css" rel="stylesheet" href="/css/jquery.filthypillow/jquery.filthypillow.css" />
@endsection

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
        </h1>
    </div>
@endsection

@section('content')
    @include('error')

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
	@endif

    <div class="row">
        <p class="lead">登録完了しました。</p>

        <div class='col-md-12'>
            <div class="well well-sm" style="height: 50px;">
                <a class="btn btn-link pull-right" href="{{ route('csvimport.index') }}"><span class="glyphicon glyphicon-backward"></span> 戻る</a>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
	<script charset="UTF-8" type="text/javascript" src="/js/moment.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery.filthypillow/jquery.filthypillow_custom.js"></script>
	<script>
	</script>

@endsection
