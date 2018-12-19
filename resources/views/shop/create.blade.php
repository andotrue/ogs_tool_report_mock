@extends('tool.common.layout')

@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">

	<link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer.css" />
	<link type="text/css" rel="stylesheet" href="/css/jquery.filer/jquery.filer-dragdropbox-theme.css" />
@endsection

@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-plus"></i>{{ $functionName }} {{ $functionSubName ? "-".$functionSubName."-" : "" }}</h1>        
		<div>
		    <a class="btn" href="{{ route('store.index') }}"><span class="glyphicon glyphicon-backward"></span> 戻る</a>
		</div>
    </div>
@endsection

@section('content')
    @include('error')

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group @if($errors->has('storename')) has-error @endif">
                    <label for="storename-field">施設名 <span class="btn-xs btn-danger">必須</span></label>
                    <input type="text" id="storename-field" name="storename" class="form-control" value="{{ old("storename") }}" required/>
                       @if($errors->has("storename"))
                        <span class="help-block">{{ $errors->first("storename") }}</span>
                       @endif
                </div>
                
                <div class="form-group @if($errors->has('comment')) has-error @endif">
                    <label for="comment-field">コメント</label>
                    <textarea class="form-control" id="comment-field" rows="3" name="comment">{{ old("comment") }}</textarea>
                       @if($errors->has("comment"))
                        <span class="help-block">{{ $errors->first("comment") }}</span>
                       @endif
                </div>
                
				<?php 
				$cnt = 1;
				for($i=1; $i<=$cnt; $i++){
				?>
				<div class="form-group @if($errors->has("image".$i)) has-error @endif">
				   <label for="image<?php echo $i; ?>-field">画像ファイル<?php echo $i; ?></label>
				      <input type="file" name="image<?php echo $i; ?>" id="filer_input<?php echo $i; ?>" multiple="multiple">
				       @if($errors->has("image".$i))
				        <span class="help-block">{{ $errors->first("image$i") }}</span>
				       @endif
				</div>
				<?php 
				}
				?>
                
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <a class="btn btn-link pull-right" href="{{ route('store.index') }}"><i class="glyphicon glyphicon-backward"></i> 戻る</a>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
	<script src="/js/jqueryfiler/jquery.filer.min.js" type="text/javascript"></script>
	
		<!-- -------------------------------画像-->
	<script type="text/javascript">
	$(document).ready(function() {
		<?php 
			$cnt = 1;
			for($i=1; $i<=$cnt; $i++){
		?>
		    $('#filer_input<?php echo $i; ?>').filer({
			    //limit: 3,
			    maxSize: 3,
			    //addMore: true,
			    extensions: ['jpg','jpeg','png','gif'],
			    //changeInput: true,
			    showThumbs: true,
				captions: {
					removeConfirmation: "削除します。よろしいですか？",
					errors: {
						filesType: "ファイル形式は、jpg,jpeg,png,gifファイルのみです。",
					}
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
					canvasImage: true,
					removeConfirmation: true,
					_selectors: {
						list: '.jFiler-items-list',
						item: '.jFiler-item',
						progressBar: '.bar',
						remove: '.jFiler-item-trash-action'
					}
				},
			});
			<?php 
			}
			?>
		});
	</script>
	
@endsection
