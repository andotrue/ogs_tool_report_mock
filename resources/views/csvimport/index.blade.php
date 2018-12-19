{{-- @{{extends('layouts.app')}} --}}
@extends('common.layout')

@section('css')
  <link type="text/css" rel="stylesheet" href="/css/jquery.filthypillow/jquery.filthypillow.css"/>
@endsection

@section('header')
  <div class="page-header clearfix">
    <h1>
      <i
        class="glyphicon glyphicon-align-justify"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
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
    <p class="lead">アップロードするファイルを選択してください。</p>
    <form action="{{ route('csvimport.store') }}" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="col-md-12">
        <?php $i = "gt"; ?>
        <input type="hidden" name="target" value="<?php echo $i; ?>">
        <div class="form-group @if($errors->has("csvfile".$i)) has-error @endif">
          <label for="csvfile<?php echo $i; ?>-field">ヘッダー行　parent_user_id,child_user_id</label>
          <input type="file" name="csvfile<?php echo $i; ?>" id="filer_input<?php echo $i; ?>" multiple="multiple">
          @if($errors->has("csvfile".$i))
            <span class="help-block">{{ $errors->first("csvfile$i") }}</span>
          @endif
        </div>
        <div class="well well-sm">
          <button type="submit" class="btn btn-primary">アップロード</button>
        </div>
      </div>

      <div class='col-md-12'>
      </div>
    </form>

  </div>
@endsection


@section('scripts')
  <script charset="UTF-8" type="text/javascript" src="/js/moment.js"></script>
  <script charset="UTF-8" type="text/javascript" src="/js/jquery.filthypillow/jquery.filthypillow_custom.js"></script>
  <script>
  </script>

@endsection
