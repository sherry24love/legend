<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">

					<h3 class="box-title"></h3>

					<div class="pull-left">
						<div class="form-inline pull-left">
							<form
								action="{{route('admin.member.checkreg')}}"
								method="get" pjax-container="">
								<fieldset>
									<div class="input-group input-group-sm">
										<span class="input-group-addon"><strong>手机号码</strong></span> <input
											type="text" class="form-control" placeholder="手机号码"
											name="keyword" value="{{request()->input('keyword')}}">
									</div>

									<div class="btn-group btn-group-sm">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</fieldset>
							</form>
						</div>

					</div>

				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					
				</div>
				<div class="box-footer clearfix">
				@if( request()->input('keyword') )
					@if( $count )
					该手机号码已经注册
					@else
					<span style="color:red;">该手机号码还未注册</span>
					@endif
				@endif	
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</div>

</section>