@extends('common.layout')

@section('css')
  <link href="/css/jquery.dataTables/jquery.dataTables.min.css" rel="stylesheet">
  <link href="/css/jquery.dataTables/dataTables.bootstrap.css" rel="stylesheet">
  <link href="/css/jquery-ui/jquery-ui.min.css" rel="stylesheet">

  <style>
    tbody:hover {
      cursor: move;
    }
  </style>
@endsection

@section('header')
  <div class="page-header clearfix">
    <h1>
      <i
        class="glyphicon glyphicon-align-justify"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
    <!--
      <a class="btn btn-success pull-right" href="{{ route('shop.create') }}"><i
          class="glyphicon glyphicon-plus"></i> 作成</a>
      -->
    </h1>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      @if($shops->count())
        <table id="table_id" class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th width="5%">ID</th>
            <th>店舗名</th>
            <!--<th class="text-right" width="3%"></th>-->
          </tr>
          </thead>

          <tbody>
          @foreach($shops as $shop)
            <?php
            $imagedetail = $shop->imagedetail;
            $thumbImg = "";
            if (isset($shop->imagedetail)) {
              $imagedetail = json_decode($imagedetail, true);
              $thumbImg = $imagedetail[0]['filename'];
              $thumbImg = "/img/shop/" . $shop->id . "/" . $thumbImg;
              $thumbImg = "<div class='col-md-3 col-xs-3'><img src='" . $thumbImg . "' class='img-thumbnail'></div>";
            }
            ?>

            <tr>
              <td>{{$shop->id}}</td>
              <td>
                <div class="row">
                  <div class='col-md-9 col-xs-9'><a
                      href="{{ route('shop.edit', $shop->id) }}">{{$shop->name}}</a></div>
                  {!! $thumbImg !!}
                </div>
              </td>
            <!--
              <td class="text-right">
              <a class="btn btn-xs btn-primary" href="{{ route('shop.show', $shop->id) }}"><i class="glyphicon glyphicon-eye-open"></i></a>
              <a class="btn btn-xs btn-warning" href="{{ route('shop.edit', $shop->id) }}"><i class="glyphicon glyphicon-edit"></i></a>
                <form action="{{ route('shop.destroy', $shop->id) }}" method="POST"
                      style="display: inline;"
                      onsubmit="if(confirm('削除します。よろしいですか？')) { return true } else {return false };">
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <button type="submit" class="btn btn-xs btn-danger"><i
                      class="glyphicon glyphicon-trash"></i></button>
                </form>
              </td>
              -->
            </tr>
          @endforeach
          </tbody>
        </table>
        {!! $shops->render() !!}
      @else
        <h3 class="text-center alert alert-info">Empty!</h3>
      @endif

    </div>
  </div>

@endsection

