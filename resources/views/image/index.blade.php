{{-- @{{extends('layouts.app')}} --}}
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
      <i class="glyphicon glyphicon-align-justify"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
      <a class="btn btn-success pull-right" href="{{ route('image.create') }}"><i class="glyphicon glyphicon-plus"></i> 作成</a>
    </h1>

  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
    @if($images->count())
      <!-- Paganation -->
        {{ $images->render() }}
        <table id="table_id" class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th width="5%">ID</th>
            <th>タイトル</th>
            <th>公開状態</th>
            <th width="16%">開始日</th>
            <th>終了日</th>
            <th width="3%" class="text-right"></th>
          </tr>
          </thead>

          <tbody>
          @foreach($images as $image)
            <?php
            $filedetail = $image->imagefiledetail;
            $filedetail = json_decode($filedetail, true);
            $thumbImg = $filedetail[0]['filename'];
            $thumbImg = "/img/image/" . $image->id . "/" . $thumbImg;
            $thumbImg = "<div class='col-md-3 col-xs-3'><img src='" . $thumbImg . "' class='img-thumbnail'></div>";
            ?>
            <tr>
              <td>{{ $image->id }}</a></td>
              <td>
                <div class="row">
                  <div class='col-md-9 col-xs-9'><a href="{{ route('image.edit', $image->id) }}">{{ $image->imagename}}</a></div>
                  {!! $thumbImg !!}
                </div>
              </td>
              <td>{{ config('const.openFlg')[$image->open_flg] }}</td>
              <td>{{ $image->open_date }}</td>
              <td>{{ $image->close_date }}</td>
              <td class="text-right">
              <!--<a class="btn btn-xs btn-primary" href="{{ route('image.show', $image->id) }}"><i class="glyphicon glyphicon-eye-open"></i></a>-->
              <!-- <a class="btn btn-xs btn-warning" href="{{ route('image.edit', $image->id) }}"><i class="glyphicon glyphicon-edit"></i></a> -->
                <form action="{{ route('image.destroy', $image->id) }}" method="POST" style="display: inline;"
                      onsubmit="if(confirm('削除してよろしいですか？')) { return true } else {return false };">
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i><!--  Delete --></button>
                </form>
              </td>

            </tr>
          @endforeach
          </tbody>
        </table>
        <!-- Paganation -->
        {!! $images->render() !!}
      @else
        <h3 class="text-center alert alert-info">Empty!</h3>
      @endif

    </div>
  </div>
@endsection

@section('scripts')
  <script src="/js/jquery.dataTables/jquery.dataTables.min.js"></script>
  <script src="/js/jquery.dataTables/dataTables.bootstrap.js"></script>
  <script src="/js/jquery-ui/jquery-ui.min.js"></script>

  <script type="text/javascript">
    $("#table_id").dataTable({
      "order": [[0, "desc"]],
      "stateSave": true,
      "oLanguage": {
        "sLengthMenu": "表示行数 _MENU_ 件",
        "oPaginate": {
          "sNext": "次のページ",
          "sPrevious": "前のページ"
        },
        "sInfo": "全_TOTAL_件中 _START_件から_END_件を表示",
        "sSearch": "検索："
      },
    });
    var table = $("#table_id").DataTable();
    if (!table.search()) {
      table.search('').draw();
    }
    table.state.clear();//ステートのクリア
  </script>

  <script type="text/javascript">
    jQuery('.jquery-ui-sortable-table').sortable();
    $('.jquery-ui-sortable-table').disableSelection();
    jQuery('#submitSortable').click(function () {
      var itemNames = '';
      var itemIDs = '';
      var inputValue = '';
      var form = document.createElement("form");
      document.body.appendChild(form);

      jQuery('.jquery-ui-sortable-table tr').map(function () {
        var id = '';
        var imagename = ''

        id = jQuery(this.childNodes[1]).text().replace(/\s+/g, "");
        imagename = jQuery(this.childNodes[5].childNodes[1]).text();
        itemNames += id + ' ' + imagename + '\n';

        inputValue += id + ',';

      });
      if (confirm(itemNames + '【この順番でよろしいですか？】')) {
        //get送信の場合
        //location . href = '?itemIDs=' + itemIDs;

        //post送信の場合
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "ids");
        input.setAttribute("value", inputValue);
        form.appendChild(input);

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "category_id");
        input.setAttribute("value", 1);
        form.appendChild(input);

        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "sort");
        input.setAttribute("value", 1);
        form.appendChild(input);

        form.setAttribute("method", "post");
        form.submit();
      }
    });
  </script>
@endsection
