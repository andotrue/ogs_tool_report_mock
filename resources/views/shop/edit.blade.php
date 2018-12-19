@extends('common.layout')

@section('css')
  <link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer.css"/>
  <link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer-dragdropbox-theme.css"/>
@endsection

@section('header')
  <div class="page-header">
    <h1><i
        class="glyphicon glyphicon-edit"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}
    </h1>
    <div>
      <a class="btn" href="{{ route('shop.index') }}"><span class="glyphicon glyphicon-backward"></span> 戻る</a>
    </div>
  </div>
@endsection

@section('content')
  @include('error')

  <div class="row">
    <div class="col-md-12">

      <form action="{{ route('shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group @if($errors->has('name')) has-error @endif">
          <label for="name-field">施設名 <span class="btn-xs btn-danger">必須</span></label>
          <input type="text" id="name-field" name="name" class="form-control"
                 value="{{ is_null(old("name")) ? $shop->name : old("name") }}"/>
          @if($errors->has("name"))
            <span class="help-block">{{ $errors->first("name") }}</span>
          @endif
        </div>

        <div class="form-group @if($errors->has('description')) has-error @endif">
          <label for="description-field">description</label>
          <textarea class="form-control" id="description-field" rows="3"
                    name="description">{{ is_null(old("description")) ? $shop->description : old("description") }}</textarea>
          @if($errors->has("description"))
            <span class="help-block">{{ $errors->first("description") }}</span>
          @endif
        </div>

        <?php
        $imagedetail_datas = @json_decode($shop->imagedetail, true);
        $cnt = 1;
        for($i=1; $i<=$cnt; $i++){
        $hd_image = "";
        if(isset($imagedetail_datas[$i-1])){
        $hd_image = json_encode($imagedetail_datas[$i-1]);
        }
        ?>
        <div class="form-group @if($errors->has("image".$i)) has-error @endif">
          <label for="image<?php echo $i; ?>-field">画像ファイル<?php echo $i; ?></label>
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

        <div class="well well-sm">
          <!--<button type="submit" class="btn btn-primary">保存</button>-->
          <a class="btn btn-link pull-right" href="{{ route('shop.index') }}"><i
              class="glyphicon glyphicon-backward"></i> 戻る</a>
        </div>
      </form>

    </div>
  </div>
@endsection

@section('scripts')
  <script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/js/jqueryfiler/jquery.filer.min.js" type="text/javascript"></script>
  <script src="/js/jqueryfiler/custom.js" type="text/javascript"></script>

  <!-- -------------------------------画像-->
  <script type="text/javascript">
      $(document).ready(function () {
        <?php
        $imagedetail_datas = @json_decode($shop->imagedetail, true);
          $cnt = 1;
          for ($i = 1; $i <= $cnt; $i++) {
              $hd_image = "";
              if (isset($imagedetail_datas[$i - 1])) {
                  $hd_image = json_encode($imagedetail_datas[$i - 1]);
              }
                  ?>
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
                  if(isset($imagedetail_datas[$i-1])){
                  ?>
                files: [
                    {
                        name: '<?php echo $imagedetail_datas[$i-1]["filename"]; ?>',
                        size: <?php echo $imagedetail_datas[$i-1]["filesize"]; ?>,
                        type: '<?php echo $imagedetail_datas[$i-1]["mimetype"]; ?>',
                        file: '/img/store/<?php echo $shop->id;?>/<?php echo $imagedetail_datas[$i-1]["filename"];?>',
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
