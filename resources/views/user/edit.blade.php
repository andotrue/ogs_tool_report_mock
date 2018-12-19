@extends('tool.common.layout')

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> {{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} #{{$user->id}}</h1>
		@if( \Auth::user()->role == "admin" )
		<p>システム管理者権限なのでフルで編集可能</p>
		@elseif( \Auth::user()->role == "store" )
		<p>施設管理者権限なのでテナント用アカウントの編集</p>
		@endif
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('user.update', $user->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('role')) has-error @endif">
	                <label for="role-field">権限 <span class="btn-xs btn-danger">必須</span></label>
					@if( \Auth::user()->role == "admin" )
 						{{Form::select('role', config('const.toolUserRole'), is_null(old("role")) ? $user->role : old("role"), array('class'=>'form-control'))}}
					@elseif( \Auth::user()->role == "store" )
		                <input type="hidden" name="role" value="{{ $user->role }}" />
		                <p>{{ config('const.toolUserRole')[$user->role] }}</p>
	                @endif
                </div>
                
                <div class="form-group @if($errors->has('store_id')) has-error @endif">
	                <label for="email-field">施設 <span class="btn-xs btn-danger">必須</span></label>
					@if( \Auth::user()->role == "admin" )
		            	{{Form::select('store_id', $stores, is_null(old("store_id")) ? $user->store_id : old("store_id"), array('class'=>'form-control'))}}
	 				@elseif( \Auth::user()->role == "store" )
	                    <input type="hidden" name="store_id" value="{{ \Auth::user()->store_id }}" />
		                <p>{{ $stores[$user->store_id] }}</p>
					@endif
                </div>
                
                <!-- テナント名 -->
                <div class="form-group @if($errors->has('shop_name')) has-error @endif">
                   <label for="shop_name-field">テナント名</label>
                   <input type="text" id="shop_name-field" name="shop_name" class="form-control" value="{{ is_null(old("shop_name")) ? $user->shop_name : old("shop_name")}}"/>
                       @if($errors->has("shop_name"))
                        <span class="help-block">{{ $errors->first("shop_name") }}</span>
                       @endif
                </div>
                
                <div class="form-group @if($errors->has('name')) has-error @endif">
                	<label for="name-field">ログイン名 <span class="btn-xs btn-danger">必須　半角英数、5～10文字</span></label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $user->name : old("name") }}"/>
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                </div>
                    
                <div class="form-group @if($errors->has('password')) has-error @endif">
                	<label for="password-field">パスワード <span class="btn-xs btn-danger">必須　変更しない場合は入力不要</span></label>
                	<input type="password" id="password-field" name="password" class="form-control" value=""/>
                		@if($errors->has("password"))
                 			<span class="help-block">{{ $errors->first("password") }}</span>
                		@endif
                </div>

				<!-- パスワード（確認） -->
                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                	<label for="password_confirmation-field">パスワード（確認） <span class="btn-xs btn-danger">必須　変更しない場合は入力不要</span></label>
                	<input type="password" id="password_confirmation-field" name="password_confirmation" class="form-control" value=""/>
                		@if($errors->has("password_confirmation"))
                 			<span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                		@endif
                </div>

                <div class="form-group @if($errors->has('email')) has-error @endif">
	                <label for="email-field">メールアドレス</label>
                    <input type="email" id="email-field" name="email" class="form-control" value="{{ is_null(old("email")) ? $user->email : old("email") }}"/>
	                    @if($errors->has("email"))
	         	           <span class="help-block">{{ $errors->first("email") }}</span>
	            	    @endif
                </div>
                
				<!-- 
                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                    <label for="password_confirmation-field">Confirm password</label>
                	<input type="password" id="password_confirmation-field" name="password_confirmation" class="form-control" value="{{ old("password_confirmation") }}" required/>
                    	@if($errors->has("password_confirmation"))
                    	<span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                   		@endif
                </div>
				 -->

				<!-- 
                <div class="form-group @if($errors->has('remember_token')) has-error @endif">
                   <label for="remember_token-field">remember_token</label>
	               <input type="text" id="remember_token-field" name="remember_token" class="form-control" value="{{ is_null(old("remember_token")) ? $user->remember_token : old("remember_token") }}"/>
                   @if($errors->has("remember_token"))
                    <span class="help-block">{{ $errors->first("remember_token") }}</span>
                   @endif
                </div>
				 -->


                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">保存</button>
                    <a class="btn btn-link pull-right" href="{{ route('user.index') }}"><i class="glyphicon glyphicon-backward"></i>  戻る</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
