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
        <p class="lead">登録対象レコード</p>
        <form action="{{ route('csvimport.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="action" value="confirm">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>parent_user_id</td>
                    <th>child_user_id</td>
                </tr>
    			<?php foreach($registration_list as $val){ ?>
                    <tr>
                        <td><?php echo $val[0]; ?></td>
                        <input type="hidden" name="regist_list1[]" value="<?php echo $val[0]; ?>">
                        <td><?php echo $val[1]; ?></td>
                        <input type="hidden" name="regist_list2[]" value="<?php echo $val[1]; ?>">
                    </tr>
    			<?php } ?>
            </table>

            <?php if(!empty($registration_list)){ ?>
        <div>よろしければ登録ボタンを押してください。</div>
            <div class="well well-sm">
                <button type="submit" class="btn btn-primary">登録</button>
                <a class="btn btn-link pull-right" href="{{ route('csvimport.index') }}"><span class="glyphicon glyphicon-backward"></span> 戻る</a>
            </div>
            <?php } ?>

        </form>

        </div>

        <p class="lead">既存レコード</p>
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>parent_user_id</td>
                    <th>child_user_id</td>
                </tr>
                <?php foreach($exist_list as $val){ ?>
                    <tr>
                        <td><?php echo $val[0]; ?></td>
                        <td><?php echo $val[1]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    </div>
@endsection


@section('scripts')
	<script charset="UTF-8" type="text/javascript" src="/js/moment.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery.filthypillow/jquery.filthypillow_custom.js"></script>
	<script>
	</script>

@endsection
