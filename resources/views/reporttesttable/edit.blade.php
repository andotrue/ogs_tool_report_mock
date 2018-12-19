@extends('common.layout')

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css"
        rel="stylesheet">
@endsection
@section('header')
  <div class="page-header">
    <h1><i
        class="glyphicon glyphicon-edit"></i> {{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
      #{{$testtable->id}}</h1>
  </div>
@endsection

@section('content')
  @include('error')

  <div class="row">
    <div class="col-md-12">

      <form action="{{ route('reporttesttable.update', $testtable->id) }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- parent_user_id -->
        <div class="form-group @if($errors->has('shop_name')) has-error @endif">
          <label for="parent_user_id-field">parent_user_id <span class="btn-xs btn-danger">必須　半角英数、5～10文字</span></label>
          <input type="text" id="parent_user_id-field" name="parent_user_id" class="form-control"
                 value="{{ is_null(old("shop_name")) ? $testtable->parent_user_id : old("parent_user_id")}}"/>
          @if($errors->has("parent_user_id"))
            <span class="help-block">{{ $errors->first("parent_user_id") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('child_user_id')) has-error @endif">
          <label for="child_user_id-field">child_user_id <span class="btn-xs btn-danger">必須　半角英数、5～10文字</span></label>
          <input type="text" id="child_user_id-field" name="child_user_id" class="form-control"
                 value="{{ is_null(old("child_user_id")) ? $testtable->child_user_id : old("child_user_id") }}"/>
          @if($errors->has("child_user_id"))
            <span class="help-block">{{ $errors->first("child_user_id") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('store_id')) has-error @endif">
          <label for="email-field">店舗（仮） <span class="btn-xs btn-danger">必須</span></label>
            {{\Form::select('store_id', $shops, is_null(old("store_id")) ? $testtable->shop_id : old("store_id"), array('class'=>'form-control'))}}
        </div>

        <div class="well well-sm">
          <button type="submit" class="btn btn-primary">保存</button>
          <a class="btn btn-link pull-right" href="{{ route('reporttesttable.index') }}"><i
              class="glyphicon glyphicon-backward"></i> 戻る</a>
        </div>
      </form>

    </div>
  </div>
@endsection
@section('scripts')
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
      $('.date-picker').datepicker({});
  </script>
@endsection
