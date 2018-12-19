@extends('tool.common.layout')

@section('css')
@endsection

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i> {{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} </h1>
		@if( \Auth::user()->role == "admin" )
		<p>システム管理者権限なのでフルで作成可能</p>
		@elseif( \Auth::user()->role == "store" )
		<p>施設管理者権限なのでテナント用アカウントの作成</p>
		@endif
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

             <form action="{{ route('user.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

				<!-- 権限 -->
				@if( \Auth::user()->role == "admin" )
                <div class="form-group @if($errors->has('role')) has-error @endif">
                    <label for="role-field">権限 <span class="btn-xs btn-danger">必須</span></label>
					{{Form::select('role', config('const.toolUserRole'), old("role"), array('class'=>'form-control','id'=>'role'))}}
				</div>
				@elseif( \Auth::user()->role == "store" )
                    <input type="hidden" name="role" value="user" />
				@endif

				<!-- 施設 -->
				@if( \Auth::user()->role == "admin" )
                <div class="form-group @if($errors->has('store_id')) has-error @endif">
                    <label for="role-field">施設 <span class="btn-xs btn-danger">必須</span></label>
					{{Form::select('store_id', $stores, old("store_id"), array('class'=>'form-control','id'=>'store_id'))}}
                </div>
				@elseif( \Auth::user()->role == "store" )
                    <input type="hidden" name="store_id" value="{{ \Auth::user()->store_id }}" />
				@endif

				<!-- テナント名 -->
                <div class="form-group @if($errors->has('shop_name')) has-error @endif">
                   <label for="shop_name-field">テナント名</label>
                   <input type="text" id="shop_name-field" name="shop_name" class="form-control" value="{{ old("shop_name") }}"/>
                       @if($errors->has("shop_name"))
                        <span class="help-block">{{ $errors->first("shop_name") }}</span>
                       @endif
                </div>
                    
				<!-- 名前 -->
                <div class="form-group @if($errors->has('name')) has-error @endif">
                    <label for="name-field">ログイン名 <span class="btn-xs btn-danger">必須</span></label>
                    <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}" />
                       @if($errors->has("name"))
                        <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                </div>
                    
				<!-- パスワード -->
                <div class="form-group @if($errors->has('password')) has-error @endif">
                    <label for="password-field">パスワード </label>
                	<input type="password" id="password-field" name="password" class="form-control" value="{{ old("password") }}" required/>
                    	@if($errors->has("password"))
                    	<span class="help-block">{{ $errors->first("password") }}</span>
                   		@endif
                </div>
                
				<!-- パスワード（確認） -->
                <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                    <label for="password_confirmation-field">パスワード（確認） <span class="btn-xs btn-danger">必須</span></label>
                	<input type="password" id="password_confirmation-field" name="password_confirmation" class="form-control" value="{{ old("password_confirmation") }}" required/>
                    	@if($errors->has("password_confirmation"))
                    	<span class="help-block">{{ $errors->first("password_confirmation") }}</span>
                   		@endif
                </div>

				<!-- EMAIL -->
                <div class="form-group @if($errors->has('email')) has-error @endif">
                   <label for="email-field">メールアドレス</label>
                   <input type="text" id="email-field" name="email" class="form-control" value="{{ old("email") }}"/>
                       @if($errors->has("email"))
                        <span class="help-block">{{ $errors->first("email") }}</span>
                       @endif
                </div>
                    
                <!--
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                <div class="form-group @if($errors->has('remember_token')) has-error @endif">
                   	<label for="remember_token-field">remember_token</label>
                	<input type="text" id="remember_token-field" name="remember_token" class="form-control" value="{{ old("remember_token") }}"/>
                   		@if($errors->has("remember_token"))
                    	<span class="help-block">{{ $errors->first("remember_token") }}</span>
                   		@endif
                </div>
                  -->
                
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <a class="btn btn-link pull-right" href="{{ route('user.index') }}"><i class="glyphicon glyphicon-backward"></i> 戻る</a>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
	//施設プルダウン”全施設”の値とその次の値を取得
	var $all_store_id;
	var $all_store_id_next;
	var obj = jQuery("select#store_id").children();
	for( var i=0; i<obj.length; i++ ){
		if(obj.eq(i).text() == "全施設"){
			$all_store_id = obj.eq(i).val();
			$all_store_id_next = obj.eq(i+1).val();
			break;
		}
	}

	store_id_change();

	//権限が"admin"なら”全施設”のみ、それ以外なら”全施設”をdisabledに
	$("select#role").change(function(){
		store_id_change();
	});

	function store_id_change(){
		if($("select#role").val() == "admin"){
			$("select#store_id").val($all_store_id);
			$("select#store_id").prop("disabled", true);
			$('<input>').attr({type: 'hidden',id: 'store_id2',name: 'store_id',value: $all_store_id}).appendTo('form');
		}
		else{
			$("select#store_id").prop("disabled", false);
			$("select#store_id").val($all_store_id_next);
			$("select#store_id").find("option[value="+$all_store_id+"]").attr('disabled', true);
			$('form [id=store_id2]').remove();
		}
		//alert("store_id2:"+$("#store_id2").val());
		//alert($("form [name=store_id]").val());
	}

  </script>
@endsection
