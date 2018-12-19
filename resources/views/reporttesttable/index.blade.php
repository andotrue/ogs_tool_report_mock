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
      <i
        class="glyphicon glyphicon-align-justify"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
      <a class="btn btn-success pull-right" href="{{ route('reporttesttable.create') }}"><i
          class="glyphicon glyphicon-plus"></i> 作成</a>
    </h1>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
    @if($testtables->count())
      <!-- Paganation -->
      {{ $testtables->render() }}
      <!-- <table class="table table-condensed table-striped"> -->
        <table id="table_id" class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th width="5%">id</th>
            <th width="5%">parent_user_id</th>
            <th>child_user_id</th>
            <th>email</th>
            <th class="text-right" width="10%"></th>
          </tr>
          </thead>
          <tbody class="jquery-ui-sortable-table">
          @foreach($testtables as $testtable)
            <tr>
              <td>{{$testtable->id}}</i></td>
              <td>{{$testtable->parent_user_id}}</i></td>
              <td>{{$testtable->child_user_id}}</i></td>
              <td>{{$testtable->email}}</i></td>
              <td class="text-right">
                <a class="btn btn-xs btn-primary" href="{{ route('reporttesttable.show', $testtable->id) }}"><i
                    class="glyphicon glyphicon-eye-open"></i></a>
                <a class="btn btn-xs btn-warning" href="{{ route('reporttesttable.edit', $testtable->id) }}"><i
                    class="glyphicon glyphicon-edit"></i></a>
                <form action="{{ route('reporttesttable.destroy', $testtable->id) }}" method="POST"
                      style="display: inline;"
                      onsubmit="if(confirm('削除します。よろしいですか？')) { return true } else {return false };">
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <button type="submit" class="btn btn-xs btn-danger"><i
                      class="glyphicon glyphicon-trash"></i><!-- Delete  --></button>
                </form>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <!-- Paganation -->
        {!! $testtables->render() !!}
      @else
        <h3 class="text-center alert alert-info">Empty!</h3>
      @endif

    </div>
  </div>

  <!-- テストメールアドレス登録 -->
  <style>
    .list-group-item {
      padding: 1px 15px;
    }

    .mailadddress_line {
      display: inline;
    }

    #bnt-mailaddress-del {
      padding: 1px 12px;
      margin-left: 50px;
    }
  </style>

  <div>
    <a type="button" class="" data-toggle="modal" data-target="#testmailaddressModal" data-whatever="@mdo">テストメール宛先編集</a>
  </div>
  <div class="modal fade" id="testmailaddressModal" tabindex="-1" role="dialog" aria-labelledby="testmailaddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="testmailaddressModalLabel">テストメール宛先編集</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form id='mailaddress-reg' action='/reporttesttable/testmailaddress_add' method='POST' class='' name='testmailaddress'>
            {{ csrf_field() }}
            <div class="form-group">
              <div id="result_ok" class="bg-success"></div>
              <div id="result_ng" class="bg-danger"></div>
              <label for="recipient-name" class="form-control-label">メールアドレス一覧:</label>
              <?php if (isset($testmailaddress)) : ?>
              <ul id="emaillist" class="list-group">
                <?php foreach ($testmailaddress as $id => $email): ?>
                <li class="list-group-item mailadddress_line<?php echo $id; ?>"
                    　id="mailadddress_line<?php echo $id; ?>">
                  <div class="mailadddress_line"><?php echo $email; ?></div>
                  <div class="mailadddress_line">
                    <a class="btn btn-danger" id="bnt-mailaddress-del" onClick="return mailaddress_del(<?php echo $id; ?>);">削除</a>
                  </div>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
              <input type="input" class="form-control" id="recipient-name" name="temail" placeholder="mail address">

              <button class="btn btn-primary">登録</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>


    </div>
  </div>

@endsection


@section('scripts')
  <script src="/js/jquery.dataTables/jquery.dataTables.min.js"></script>
  <script src="/js/jquery.dataTables/dataTables.bootstrap.js"></script>
  <script src="/js/jquery-ui/jquery-ui.min.js"></script>

  <script type="text/javascript">
      $("#table_id").dataTable({
          "order": [[0, "asc"]],
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
          lengthChange: false,
          paging: false,
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
              var title = ''

              id = jQuery(this.childNodes[1]).text().replace(/\s+/g, "");
              title = jQuery(this.childNodes[5].childNodes[1]).text();
              itemNames += id + ' ' + title + '\n';

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

  <script>
      var $form = $("#mailaddress-reg");
      var $button = $form.find('button');
      $button.attr('disabled', false);

      //テストメールアドレス削除操作
      function mailaddress_del($id) {
          var $button = $('a');

          if (confirm($id + ':削除してよろしいですか')) {
              // 送信
              $.ajax({
                  url: '/reporttesttable/testmailaddress_del/' + $id,
                  type: 'get',
                  data: 'delay=1',  // （デモ用に入力値をちょいと操作します）
                  timeout: 10000,  // 単位はミリ秒

                  // 送信前
                  beforeSend: function (xhr, settings) {
                      // ボタンを無効化し、二重送信を防止
                      $button.attr('disabled', true);
                  },
                  // 応答後
                  complete: function (xhr, textStatus) {
                      // ボタンを有効化し、再送信を許可
                      $button.attr('disabled', false);
                  },

                  // 通信成功時の処理
                  success: function (result, textStatus, xhr) {
                      console.dir(result);
                      var result = JSON.parse(result);
                      //alert(result['status']);
                      if (result['status'] == 'success') {
                          $('#result_ok').text('id:' + result['id'] + 'を削除しました');
                          $('#result_ng').text('');

                          var listId = $('#emaillist li.mailadddress_line' + result['id']);
                          listId.remove();
                      }
                      else if (result['status'] == 'error') {
                          $('#result_ok').text('');
                          $('#result_ng').text(result['message']);
                      }
                  },

                  // 通信失敗時の処理
                  error: function (xhr, textStatus, error) {
                      alert(xhr);
                      $('#result_ok').text('');
                      $('#result_ng').text('error');
                  }
              });
          }
      }

      //テストメールアドレス登録操作
      $('#mailaddress-reg').submit(function (event) {
          // HTMLでの送信をキャンセル
          event.preventDefault();

          // 操作対象のフォーム要素を取得
          var $form = $(this);

          // 送信ボタンを取得
          // （後で使う: 二重送信を防止する。）
          var $button = $form.find('button');
          console.dir($form);

          // 送信
          $.ajax({
              url: $form.attr('action'),
              type: $form.attr('method'),
              data: $form.serialize()
                  + '&delay=1',  // （デモ用に入力値をちょいと操作します）
              timeout: 10000,  // 単位はミリ秒

              // 送信前
              beforeSend: function (xhr, settings) {
                  // ボタンを無効化し、二重送信を防止
                  $button.attr('disabled', true);
              },
              // 応答後
              complete: function (xhr, textStatus) {
                  // ボタンを有効化し、再送信を許可
                  $button.attr('disabled', false);
              },

              // 通信成功時の処理
              success: function (result, textStatus, xhr) {
                  var result = JSON.parse(result);
                  console.dir(result);
                  // 入力値を初期化
                  $form[0].reset();

                  alert(result['status']);
                  if (result['status'] == 'success') {
                      $('#result_ok').text(result['message']);
                      $('#result_ng').text('');
                      //$(".modal-body .form-group ul").append("<li>"+result['message']+"</li>");
                      $id = result['id'];
                      $email = result['email'];

                      var appned_li = "<li class='list-group-item mailadddress_line" + $id + "'　id='mailadddress_line" + $id + "'>" +
                          "<div class='mailadddress_line'>" + $email + "</div>" +
                          "<div class='mailadddress_line'>" +
                          "<a class='btn btn-danger' id='bnt-mailaddress-del' onClick='return mailaddress_del(" + $id + ");'>削除</a>" +
                          "</div>" +
                          "</li>";
                      $(".modal-body .form-group ul").append(appned_li);
                  }
                  else if (result['status'] == 'error') {
                      $('#result_ok').text('');
                      $('#result_ng').text(result['message']);
                  }
              },

              // 通信失敗時の処理
              error: function (xhr, textStatus, error) {
                  console.dir(xhr);
                  $('#result_ok').text('');
                  $('#result_ng').text('通信失敗');
              }
          });
      });
      /*
      var c = "";
      $(document).ready(function(){
          $('.testmailaddressbtn').click(function(){
          c = $('.testmailaddressbtn').attr('class');
          //alert(c);
          if( c.match(/btn-default/) ){
            $('.testmailaddressbtn').removeClass('btn-default');
            $('.testmailaddressbtn').addClass('btn-success');
          }
          else{
            $('.testmailaddressbtn').removeClass('btn-success');
            $('.testmailaddressbtn').addClass('btn-default');
          }
          });

      });
      */

  </script>

@endsection
