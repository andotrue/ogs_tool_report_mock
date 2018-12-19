@extends('common.layout')

@section('css')
  <link type="text/css" rel="stylesheet" href="/css/jquery.filthypillow/jquery.filthypillow.css"/>

  <link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer.css"/>
  <link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer-dragdropbox-theme.css"/>
@endsection

@section('header')
  <div class="page-header">
    <h1><i class="glyphicon glyphicon-edit"></i> {{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }} #{{$image->id}}</h1>
    <div>
      <a class="btn" href="{{ route('image.index') }}"><span class="glyphicon glyphicon-backward"></span> 戻る</a>
    </div>
  </div>
@endsection

@section('content')
  @include('error')
  <div class="row">
    <form action="{{ route('image.update', $image->id) }}" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="col-md-8">
        <div class="form-group @if($errors->has('title')) has-error @endif">
          <label for="imagename-field">タイトル <span class="btn-xs btn-danger">必須</label></label>
          <input type="text" id="imagename-field" name="imagename" class="form-control"
                 value="{{ is_null(old("imagename")) ? $image->imagename : old("imagename") }}"/>
          @if($errors->has("imagename"))
            <span class="help-block">{{ $errors->first("imagename") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('category')) has-error @endif">
          <label for="category-field">カテゴリ <span class="btn-xs btn-danger">必須</span></label>
          {{Form::select('category', config('const.imageCategory'), old("category"), array('class'=>'form-control'))}}
        </div>

        <?php
        $filedetail_datas = @json_decode($image->filedetail, true);
        $cnt = 1;
        for($i=1; $i<=$cnt; $i++){
        $hd_image = "";
        if(isset($filedetail_datas[$i-1])){
        $hd_image = json_encode($filedetail_datas[$i-1]);
        $linkurl = $filedetail_datas[0]['linkurl'];
        $link_new_window = $filedetail_datas[0]['link_new_window'];
        }
        ?>
        <div class="form-group @if($errors->has('linkurl')) has-error @endif">
          <label for="linkurl-field">リンクURL</label>
          <input type="url" id="linkurl-field" name="linkurl" class="form-control" value="{{ is_null(old("linkurl")) ? $linkurl : old("linkurl") }}"/>
          @if($errors->has("linkurl"))
            <span class="help-block">{{ $errors->first("linkurl") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('open_flg')) has-error @endif">
          <label for="link_new_window-field">新規ウィンドウ <span class="btn-xs btn-danger">必須</span></label>
          {{Form::select('link_new_window', config('const.linkNewWindow'), is_null(old("link_new_window"))? $link_new_window : old("link_new_window") , array('class'=>'form-control'))}}
        </div>

        <div class="form-group @if($errors->has("image".$i)) has-error @endif">
          <label for="image<?php echo $i; ?>-field">画像ファイル<?php echo $i; ?> <span class="btn-xs btn-danger">必須</span></label>
          <input type="file" name="image<?php echo $i; ?>" id="filer_input<?php echo $i; ?>" multiple="multiple">
          @if($errors->has("image".$i))
            <span class="help-block">{{ $errors->first("image$i") }}</span>
          @endif
          <input type="hidden" id="hd_image<?php echo $i; ?>-field" name="hd_image<?php echo $i; ?>"
                 value="{{ is_null(old("hd_image".$i)) ? $hd_image : old("hd_image".$i) }}"/>
        </div>
      <?php
			}
			?>
      </div>

      <div class='col-md-4'>
        <div class="form-group @if($errors->has('open_flg')) has-error @endif">
          <label for="open_flg-field">公開状態 <span class="btn-xs btn-danger">必須</span></label>
          {{Form::select('open_flg', config('const.openFlg'), is_null(old("open_flg")) ? $image->open_flg : old("open_flg"), array('class'=>'form-control'))}}
          @if($errors->has("open_flg"))
            <span class="help-block">{{ $errors->first("open_flg") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('open_date')) has-error @endif">
          <label for="open_date-field">掲載開始日 <span class="btn-xs btn-danger">必須</span></label>
          <input type="text" id="open_date-field" name="open_date" class="form-control filthypillow-1"
                 value="{{ is_null(old("open_date")) ? $image->open_date : old("open_date") }}"/>
          @if($errors->has("open_date"))
            <span class="help-block">{{ $errors->first("open_date") }}</span>
          @endif
          <label for="open_date-field">掲載終了日 </label>
          <input type="text" id="close_date-field" name="close_date" class="form-control filthypillow-2"
                 value="{{ is_null(old("close_date")) ? $image->close_date : old("close_date") }}"/>
          @if($errors->has("close_date"))
            <span class="help-block">{{ $errors->first("close_date") }}</span>
          @endif
        </div>
      </div>

      <div class='col-md-12'>
        <div class="well well-sm">
          <button type="submit" class="btn btn-primary">Save</button>
          <a class="btn btn-link pull-right" href="{{ route('image.index') }}"><i class="glyphicon glyphicon-backward"></i> 戻る</a>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
  <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/js/jqueryfiler/jquery.filer.min.js" type="text/javascript"></script>
  <script src="/js/jqueryfiler/custom.js" type="text/javascript"></script>

  <script charset="UTF-8" type="text/javascript" src="/js/moment.js"></script>
  <script charset="UTF-8" type="text/javascript" src="/js/jquery.filthypillow/jquery.filthypillow_custom.js"></script>

  <script charset="UTF-8" type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>


  <!-- -------------------------------開始日付 -->
  <script type="text/javascript">
    var $fp = $(".filthypillow-1"),
      now = moment().subtract("seconds", 1);

    $fp.filthypillow({
      minDateTime: function () {
        return moment().subtract("days", 3650);
      },
      startStep: "month",
      steps: ["year", "month", "day", "hour", "minute", "second"],
      initialDateTime: function (m) {
        $today = moment().format('YYYY/MM/DD 00:00:00');
        //return "2015-01-01 00:00:00";
        //return moment().format('YYYY/MM/DD 00:00:00');
        //return '{{ is_null(old("open_date")) ? $image->open_date : "moment().format('YYYY/MM/DD 00:00:00')" }}';
        //alert("/"+"{{ is_null(old("open_date")) }}"+"/");
        //return {{ is_null(old("open_date")) ? $image->open_date : (old("open_date")? old("open_date") : '$today') }};
        return <?php echo is_null(old("open_date")) ? "'".$image->open_date."'" : (old("open_date")? "'".old("open_date")."'" : "'".$image->open_date."'") ?>;
      },
      calendar: {
        isPinned: false,
        saveOnDateSelect: true
      },
    });

    $fp.on("focus", function () {
      $fp.filthypillow("show");
    });

    $fp.on("fp:save", function (e, dateObj) {
      $fp.val(dateObj.format("YYYY/MM/DD HH:mm:ss"));
      $fp.filthypillow("hide");
      //$fp.val( dateObj.format( "MMM DD YYYY hh:mm A" ) );
      //$fp.filthypillow( "hide" );
      //console.info( dateObj );

    });
  </script>

  <!-- -------------------------------終了日付 -->
  <script type="text/javascript">
    var $fp2 = $(".filthypillow-2"),
      now = moment().subtract("seconds", 1);

    $fp2.filthypillow({
      minDateTime: function () {
        return moment().subtract("days", 3650);
      },
      startStep: "month",
      steps: ["year", "month", "day", "hour", "minute", "second"],
      initialDateTime: function (m) {
        $today = moment().format('YYYY/MM/DD 00:00:00');
        //alert(String($today));
        //return moment().add(1,'years').format('YYYY-MM-DD 00:00:00');
        //return moment().format('YYYY/MM/DD 00:00:00');
        //return {{ is_null(old("close_date")) ? ($image->close_date)? "$image->close_date" : "\$today" : "old('close_date')" }};
        return <?php echo is_null(old("close_date")) ? (($image->close_date)? "'".$image->close_date."'" : '$today') : (old("close_date")? "'".old("close_date")."'" : "'".$image->close_date."'") ?>;
      },
    });

    $fp2.on("focus", function () {
      $fp2.filthypillow("show");
    });

    $fp2.on("fp:save", function (e, dateObj) {
      $fp2.val(dateObj.format("YYYY/MM/DD HH:mm:ss"));
      $fp2.filthypillow("hide");
    });
  </script>


  <!-- -------------------------------画像-->
  <script type="text/javascript">
    $(document).ready(function () {
      <?php
      $filedetail_datas = @json_decode($image->filedetail, true);
      $cnt = 1;
      for ($i = 1; $i <= $cnt; $i++) {
        $hd_image = "";
        if (isset($filedetail_datas[$i - 1])) {
          $hd_image = json_encode($filedetail_datas[$i - 1]);
        }
          ?
      >
        $("#hd_image<?php echo $i;?>-field").val('<?php echo $hd_image; ?>');
        $('#filer_input<?php echo $i; ?>').filer({
          //limit: 3,
          maxSize: 3,
          //addMore: true,
          extensions: ['jpg', 'jpeg', 'png', 'gif'],
          //changeInput: true,
          showThumbs: true,
          captions: {
            removeConfirmation: "削除します。よろしいですか？",
            errors: {
              filesType: "ファイル形式は、jpg,jpeg,png,gifファイルのみです。",
            }
          },
          onRemove: false,
          <?php
            if(isset($filedetail_datas[$i-1])){
            ?>
          files: [
            {
              name: '<?php echo $filedetail_datas[$i-1]["filename"]; ?>',
              size: <?php echo $filedetail_datas[$i-1]["filesize"]; ?>,
              type: '<?php echo $filedetail_datas[$i-1]["mimetype"]; ?>',
              file: '/img/image/<?php echo $image->id;?>/<?php echo $filedetail_datas[$i-1]["filename"];?>',
            }
          ],

          onRemove: function (itemEl, file, id, listEl, boxEl, newInputEl, inputEl) {
            $("#hd_image<?php echo $i;?>-field").val("");
          },
          //onRemove: false,
          templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
							<div class="jFiler-item-container">\
								<div class="jFiler-item-inner">\
									<div class="jFiler-item-thumb">\
										<div class="jFiler-item-status"></div>\
										<div class="jFiler-item-thumb-overlay">\
											<div class="jFiler-item-info">\
												<div style="display:table-cell;vertical-align: middle;">\
													<span class="jFiler-item-title"><b title="<?php echo "{{"; ?>fi-name<?php echo "}}"; ?>"><?php echo "{{"; ?>fi-name<?php echo "}}"; ?></b></span>\
													<span class="jFiler-item-others"><?php echo "{{"; ?>fi-size2<?php echo "}}"; ?></span>\
												</div>\
											</div>\
										</div>\
										<?php echo "{{"; ?>fi-image<?php echo "}}"; ?>\
									</div>\
									<div class="jFiler-item-assets jFiler-row">\
										<ul class="list-inline pull-left">\
											<li><?php echo "{{"; ?>fi-progressBar<?php echo "}}"; ?></li>\
										</ul>\
										<ul class="list-inline pull-right">\
											<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
										</ul>\
									</div>\
								</div>\
							</div>\
						</li>',

            itemAppend: '<li class="jFiler-item" style="width:49%">\
						    <div class="jFiler-item-container">\
						        <div class="jFiler-item-inner">\
						            <div class="jFiler-item-thumb">\
						                <div class="jFiler-item-status"></div>\
						                <div class="jFiler-item-thumb-overlay">\
											<div class="jFiler-item-info">\
												<div style="display:table-cell;vertical-align: middle;">\
													<span class="jFiler-item-title"><b title="<?php echo "{{"; ?>fi-name}}"><?php echo "{{"; ?>fi-name<?php echo "}}"; ?></b></span>\
													<span class="jFiler-item-others"><?php echo "{{"; ?>fi-size2<?php echo "}}"; ?></span>\
												</div>\
											</div>\
										</div>\
										<?php echo "{{"; ?>fi-image<?php echo "}}"; ?>\
						            </div>\
						            <div class="jFiler-item-assets jFiler-row">\
						                <ul class="list-inline pull-left">\
						                    <li><span class="jFiler-item-others"><?php echo "{{"; ?>fi-icon}}</span></li>\
						                </ul>\
						                <ul class="list-inline pull-right">\
						                    <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
						                </ul>\
						            </div>\
						        </div>\
						    </div>\
						</li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
              list: '.jFiler-items-list',
              item: '.jFiler-item',
              progressBar: '.bar',
              remove: '.jFiler-item-trash-action'
            }
          },
          <?php
          }
          ?>
        });
        <?php
        }
        ?>
      }
    );
  </script>

@endsection
