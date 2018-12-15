<?php

namespace App\Admin\Controllers;
use App\Support\Sms;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Form\Builder;
use App\Models\Order ;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Box;
use App\Models\Ship;
use App\Models\Company;
use App\User;
use App\Models\Reward;
use App\Models\Refund;
use App\Models\Flight;
use App\Models\Port;
use App\Models\OrderChangeLog;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Form\Tools;
use App\Admin\Extensions\Tools\OrderState;
use App\Models\Finance;
use Encore\Admin\Form\Field\DateRange;
use App\Admin\Extensions\OrderExport;

class OrderController extends Controller {
	/**
	 * Index interface.
	 *
	 * @return Content
	 */
	public function index() {
		return Admin::content(function (Content $content) {

			$content->header('订单管理');
			$content->description('列表');

			$content->body($this->grid());
		});
	}

	/**
	 * Edit interface.
	 *
	 * @param $id
	 * @return Content
	 */
	public function edit($id)
	{
		return Admin::content(function (Content $content) use ($id) {

			$content->header('订单管理');
			$content->description('编辑');
			$content->body($this->createform()->edit($id));
		});
	}

	public function copynew( $id ) {
		return Admin::content(function (Content $content) use ($id) {

			$content->header('订单管理');
			$content->description('新增');
			$form = $this->createform() ;
			$form->setAction(route('order.create') );

			$form->edit($id);
			$form->builder()->setMode('create');
			$form->builder()->field('waybill')->value('');
			$form->builder()->field('company_id')->value( 0 );
			$form->builder()->field('ship_id')->value( 0 );
			$form->builder()->field('voyage')->value('');
			$form->builder()->fields()->each(function ( $field) {
				if( $field instanceof DateRange ) {
					$field->fill( ['start_time' => '' , 'end_time' => ''] );
				}
			});
			//$form->builder()->field('start_time')->value('');
			//dd( $form->builder()->field('start_time') ) ;
			//$form->builder()->field(send_time')->value('');
			$form->builder()->field('ship_cost')->value('');
			$form->builder()->field('trailer_cost')->value('');
			$form->builder()->field('other_cost')->value('');
			$form->builder()->field('costinfo')->value('');
			$form->builder()->field('cabinet_no')->value('');
			$form->builder()->field('seal_num')->value('');
			$form->builder()->field('order_sn')->value('');

			//订单状态为初始状态
			$form->builder()->field('state')->value( 0 );
			
			//订单返利金额为0
			$form->builder()->field('rebate')->value( 0 );
			//重置返利状态
			$form->builder()->field('rebate_status')->value( 0 );
			//
			$content->body( $form );
		});
	}

	/**
	 * Create interface.
	 *
	 * @return Content
	 */
	public function create()
	{
		return Admin::content(function (Content $content) {

			$content->header('订单管理');

			$content->description('创建');
			$form = $this->createform() ;
			$content->body( $form );
		});
	}

	/**
	 * 订单确
	 */
	protected function createform() {
		/**
		 * ALTER TABLE `sherry`.`order`
ADD COLUMN `barge_port` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '驳船中转港口' AFTER `shipment`,
ADD COLUMN `barge_plan_time` VARCHAR(45) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL COMMENT '驳船预计离港时间' AFTER `state`;

		 */
		return Admin::form( Order::class, function (Form $form) {
			$form->tab( '基本信息' , function( Form $form ){
				if( request()->input('split') == 1 ) {
					$form->hidden( 'created_at' );
				}
				$form->text('user.name' , '用户手机号码' )->rules('required')->help("请输入准确的手机号码，如果号码不存在则会自动创建一个用户");

				// $form->text('destination' , '目的地' );
				$form->select ( 'transport_protocol', '运输协议' )->options ( config ( 'global.transport_protocol' ) );
				$form->select ( 'goods_kind', '货物类型' )->options ( config ( 'global.goods_kind' ) );
				$states = [
						'on' => [
								'value' => 1,
								'text' => trans ( 'cms::lang.yes' ),
								'color' => 'success'
						],
						'off' => [
								'value' => 0,
								'text' => trans ( 'cms::lang.no' ),
								'color' => 'danger'
						]
				];
				$form->hidden ( 'rebate_status' )->default ( 0 );
				$form->text ( 'owner', '货主' );
				// $form->switch('rebate_status' , '是否返利' )->states( $states )->default( 0 );
				$form->currency ( 'rebate', '返利金额' )->symbol ( "￥" )->default ( 0 )->help ( '如果有返利请填写' );
				$form->text ( 'remark', '备注' );
			} );

			$form->tab ( '货船信息', function (Form $form) {

				$form->select ( 'shipment', '起运港口' )->options ( function () {
					return Port::where ( 'parent_id', '>', 0 )->pluck ( 'name' , 'id' );
				});

				$form->select('barge_port' , '起运中转港口')->options( function(){
					return collect( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' ) )->prepend( '请选择' , 0 );
				})->help("如果是直达则不需要选择起运中转港口(大船起始港)");
				//$form->text('barge_flight' , '起运驳船航次' )->help("如果存在起运驳船则填写");

				$form->select('barge_to_port' , '到港中转港口')->options( function(){
					return collect( Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' ) )->prepend( '请选择' , 0 );
				})->help("如果是直达则不需要选择到港中转港口(大船目的港)");



				$form->select('destinationport' , '目的港口')->options( function(){
					return Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id' );
				});

			});

			$form->tab('货物信息' , function( Form $form ){
				$form->text('goods.name' , '货物名称' )->rules('required');
				$form->select('goods.box_type' , '货物箱型' )->options( config('global.box_type' ) );
				$form->number('goods.box_num' , '箱量' )->rules('required');
				$form->number('goods.total_num' , '总数量' );
				$form->number('goods.weight' , '单柜毛重' )->rules('required');
				$form->number('goods.cubage' , '货物总体积' );
				$form->text('goods.package' , '货物包装类型' )->rules('required') ;
			} );
			$form->tab( '委托人信息' , function( Form $form ) {
				$form->text('entrust.name' , '委托人名称' )->rules('required');
				$form->text('entrust.contact' , '委托联系人' )->rules('required' );
				$form->text('entrust.mobile' , '委托人电话' )->rules('required');
			});

			$form->tab('发货人信息' , function( Form $form ) {
				$form->text('sender.name' , '发货人名称' );
				$form->text('sender.contact_name' , '发货人联系人' );
				$form->text('sender.mobile' , '发货人联系电话' );
				$form->text('sender.email' , '发货人联系邮箱' );
				$form->text('sender.address' , '装货地址' )->help("如果是门到港或者门到门请一定要填写");
				$form->date('sender.load_date' , '装货日期')->rule('required') ;
			});

			$form->tab('收货人信息' , function( Form $form ) {
				$form->text('recevier.name' , '收货人名称' )->rules('required');
				$form->text('recevier.contact_name' , '收货人联系人' )->rules('required');
				$form->text('recevier.mobile' , '收货人联系电话' )->rules('required');
				$form->text('recevier.email' , '收货人联系邮箱' );
				$form->text('recevier.address' , '送货地址' )->help("如果是到门请一定要填写");
				$form->text('recevier.id_no' , '收货人证件号码') ;
			});

			$form->tab('船公司信息' , function( Form $form ){
				$form->text('waybill' , '运单号' )->help("如果是处理中，填写运单号后订单状态会变成已确认");
				$form->select('company_id' , '船公司')->options( function(){
					$result = \App\Models\Company::pluck('name' , 'id' )->toArray();
					return array_prepend( $result , '请选择' , 0 );
				})->load('ship_id' , route('order.ship') ) ;
				$form->select('ship_id' , '大船船名')->options( function( $id ) use( $form ) {

					if( $form->model()->company_id ) {
						$result = \App\Models\Ship::where('company_id' , $form->model()->company_id )->pluck('name' , 'id' )->toArray();
					} else {
						$result = [] ;
					}
					return array_prepend( $result , '请选择' , 0 );
				});


				$form->text('voyage' , '大船航次');

				$form->date('barge_plan_time' , '起运港驳船预计离港')->format('YYYY-MM-DD HH:mm')->dataType ('text' );
				$form->date('barge_time' , '起运港驳船实际离港')->format('YYYY-MM-DD HH:mm')->dataType ('text' );
				$form->date('start_time' , '预计大船离港时间')->format('YYYY-MM-DD HH:mm') ;
				$form->date('end_time' , '预计大船到港时间')->format('YYYY-MM-DD HH:mm');
				$form->text('barge_to_flight' , '目的港驳船船名/航次')->hepl("如果存在到港驳船则填写");
				$form->date('barge_to_plan_time' , '目的港驳船预计到港')->format('YYYY-MM-DD HH:mm')->dataType ('text' );
				$form->date('barge_to_time' , '目的港驳船实际到港')->format('YYYY-MM-DD HH:mm')->dataType ('text' );
				$form->currency('ship_cost' , '海运费用')->symbol("￥");
				$form->currency('trailer_cost' , '拖车费用')->symbol("￥");
				$form->currency('other_cost' , '其他费用' )->symbol("￥");
				$form->text('costinfo' , '费用说明' );
				$form->text('cabinet_no' , '柜号' )->help('多个请用半角逗号隔开');
				$form->text('seal_num' , '封条号' )->help('多个请用半角逗号隔开');
				$form->text('order_sn' , '富裕通工作号')->help('富裕通工作号');
				$form->text('barge_to_remark' , '派送说明' );

			});

			$form->tab('保险信息' , function( Form $form ) {
				$states = [
						'on'  => ['value' => 1, 'text' => trans('cms::lang.yes') , 'color' => 'success'],
						'off' => ['value' => 0, 'text' => trans('cms::lang.no') , 'color' => 'danger'],
				];
				$form->switch('enable_ensure' , '是否需要保险' )->states( $states );
				$form->text('ensure_name' , '保险人姓名' );
				$form->currency('insure_goods_worth' , '保险物品价值')->symbol("￥")->default( 0 );
				$form->hidden('state' )->default( 0 );
			} );
			$form->tools( function( Tools $tools ) {
				$button =<<<EOT
<div class="btn-group pull-right" style="margin-right: 10px">
    <a class="btn btn-sm btn-default form-file"><i class="fa fa-file"></i>&nbsp;存为待处理</a>
</div>
EOT;

				//$tools->add( $button );
				$script =<<<SCRIPT
$('.form-file').click(function(){
	$('input[name="state"]').val( 10 );
	$('button[type="submit"]').trigger('click');
});
SCRIPT;
			Admin::script( $script );
			});
			$form->file('excel' , '订单确认涵' );
		});
	}

	public function update( $id , Request $request ) {
		$form = $this->createform();
		$form->ignore('user.name');
		$form->saving( function( $form ){
			//$form->input('start_time' ) ? $form->input('start_time' , strtotime( $form->input('start_time') ) ) : $form->input('start_time' , 0 );
			//$form->input('start_time' ) ? $form->input('end_time' , strtotime( $form->input('end_time') ) ) : $form->input('end_time' , 0 );
			if( $form->input('waybill') && $form->model()->state < 2 ) {
				$form->model()->state = 2 ;
			}
			if( !$form->input('barge_time' ) ) {
				$form->model()->barge_time = '';
			}
		});
		return $form->update( $id );
	}

	public function ship( Request $request ) {
		$companyId = $request->get('q');
		$collect = \App\Models\Ship::where('company_id', $companyId)->get(['id', DB::raw('name as text')]);
		$collect = collect( $collect )->prepend( ['text' => '待选择' , 'id' => 0 ]) ;
		return $collect ;
	}
	public function flight( Request $request ) {
		$shipId = $request->get('q');
		return \App\Models\Flight::where('ship_id' , $shipId )->get(['id', DB::raw('no as text')]);
	}

	public function store( Request $request ) {
		$form = $this->createform() ;
		$form->ignore('user.name') ;
		$form->saving( function( $form ){
			$name = Input::get('user.name');
			$user = User::where('name' , $name )->first();
			if( empty( $user ) ) {
				//如果用户不存在则新建一个用户
				$user = User::create([
						'name' => $name ,
						'password' => bcrypt( '888888' ) ,
				]);
			}
			if( $form->input('waybill') && $form->model()->state < 2 ) {
				$form->model()->state = 2 ;
			}
			if( $form->input('created_at') ) {
				$form->model()->created_at = $form->input('created_at') ;
			}


			$form->model()->user_id = $user->id ;

		});
		return $form->store();
	}

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		Admin::script( view('admin.script.orderlist')->render() );
		return Admin::grid( Order::class, function (Grid $grid) {
			$tabCate = request('tabcate' , 'all' );
			switch( $tabCate ) {
				case 'waitdeal':
					$grid->model()->where('state' , 0 );
					break ;
				case 'ontrace' :
					$grid->model()->where('is_finished' , 0 )->where('state' , '>' , 0 )->where('state' , '<' , 9 );
					break ;
				case 'tracedone' :
					$grid->model()->where('is_finished' , 1 )->where('state' , '>' , 0 )->where('state' , '<' , 9 );
					break ;
				case 'trash' :
					$grid->model()->where('state' , 9 );
					break ;
				default :
					$grid->model()->where('state' , '<>' , 9 );

			}
            $userId = request('user_id');
            if( $userId ) {
                $grid->model()->where('user_id' , $userId ) ;
            }
			$grid->model()->orderBy('id' , 'desc');
			$grid->model()->with('toport' , 'ship' , 'admin' , 'fromport' , 'goods' , 'entrust' , 'sender' , 'recevier' );
			$grid->id('ID')->sortable();
			$grid->column( 'fromport.name' , '起运港');
			$grid->column( 'toport.name' , '目的港');
			$grid->column('ship.name' , '船名');
			$grid->voyage('航次');
			$grid->waybill('运单号');
			$grid->order_sn('工作号');
			$grid->column('admin.username' , '客服名称' );
			$grid->state('状态')->display( function( $v ){
				return data_get( config('global.order_state') , $v );
			});

			$grid->created_at('创建时间');
			$grid->updated_at('修改时间');
			$grid->disableBatchDeletion();
			$grid->exporter( new OrderExport() );
			//$grid->disableExport();
			//$grid->disableCreation();

			$grid->tools(function ($tools) {
				$tools->append(  new OrderState() );
			});
			$grid->filter( function( $filter){
				$filter->disableIdFilter();
				$filter->equal('order_sn' , '订单编号');
				$filter->like('waybill' , '运单号');
				$filter->is('ship_id' , '船名' )->select(
					Ship::pluck('name' , 'id' )
				);
				$filter->equal('voyage' , '航次');
				$filter->where( function( $query ){
					$input = $this->input ;
					return $query->whereIn('user_id' , function( $query) use ( $input ) {
						return $query->from('users')->where('name' , 'like' , "%$input%" )->select('id');
					});
				} , '用户手机' );
				$filter->where( function( $query ){
					$input = $this->input ;
					return $query->whereIn('admin_id' , function( $query) use ( $input ) {
						return $query->from('admin_users')->where('username' , 'like' , "%$input%" )->select('id');
					});
				} , '客服名称' );
				$filter->is('shipment' , '起运港' )->select( function(){
					return Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id');
				} );
				$filter->is('destinationport' , '目的港' )->select( function(){
					return Port::where('parent_id' , '>' , 0 )->pluck('name' , 'id');
				} );
				$filter->is('state' , '订单状态')->select( config( 'global.order_state') );
				$filter->between('created_at' , '创建时间')->dateTime();
				//$filter->useModal();
			} );
			$grid->actions( function( $actions ){

				//$actions->disableEdit();
				$actions->disableDelete();
				$importUrl = route('admin.order.import' , ['id' => $actions->row->id ] ) ;
				$showUrl = route('admin.order.show' , ['id' => $actions->row->id ] );
				$actions->append("<a href='{$showUrl}' class='btn btn-xs btn-primary'>详情</a>&nbsp;");
				$dealUrl = route('admin.order.deal' , ['id' => $actions->row->id ] );
				$destoryUrl = route('admin.order.delete' , ['id' => $actions->row->id ] );
				$backUrl = route('admin.order.back' , ['id'=> $actions->row->id ] );
				$okUrl = route('admin.order.take' , ['id' => $actions->row->id ] );
				$finishedUrl = route('admin.order.finished' , ['id' => $actions->row->id ] );
				if( $actions->row->state == 0 ) {
					$actions->append("<a data-href='{$dealUrl}' class='btn btn-xs btn-primary order-deal'>处理</a>&nbsp;");
					$actions->append("<a data-href='{$backUrl}' class='btn btn-xs btn-primary order-back'>返单</a>&nbsp;");
					$actions->append("<a data-href='{$destoryUrl}' class='btn btn-xs btn-danger order-fail'>作废</a>&nbsp;");
				}
				if( $actions->row->state == 1 ) {
					$actions->append("<a data-href='{$backUrl}' class='btn btn-xs btn-primary order-back'>返单</a>&nbsp;");
					$actions->append("<a href='{$importUrl}' class='btn btn-xs btn-primary order-import'>导入运单</a>&nbsp;");
					$actions->append("<a data-href='{$destoryUrl}' class='btn btn-xs btn-danger order-fail'>作废</a>&nbsp;");
				}
				if( $actions->row->state == 2 ) {
					$sendUrl = route('admin.order.send', ['id' => $actions->row->id ] );
					//$actions->append("<a data-href='{$backUrl}' class='btn btn-xs btn-primary order-back'>返单</a>&nbsp;");
					$actions->append("<a href='{$importUrl}' class='btn btn-xs btn-primary order-import'>重新导入</a>&nbsp;");
					$actions->append("<a data-href='{$sendUrl}' class='btn btn-xs btn-primary order-ok'>出货</a>&nbsp;");
					$actions->append("<a data-href='{$destoryUrl}' class='btn btn-xs btn-danger order-fail'>作废</a>&nbsp;");
				}
				if( $actions->row->state == 3 ) {
					$actions->append("<a data-href='$okUrl' class='btn btn-xs btn-danger order-take'>收款</a>&nbsp;");
				}
				if( $actions->row->is_finished == 0 ) {
					$actions->append("<a data-href='$finishedUrl' class='btn btn-xs btn-danger order-tracedone'>完成追踪</a>&nbsp;");
				} else {
					$actions->disableEdit();
				}

				$copynew = route('order.copy' , ['id' => $actions->row->id ]);
				$actions->append("<a href='$copynew' class='btn btn-xs btn-primary'>复制新增</a>&nbsp;");
				$copynew = route('order.copy' , ['id' => $actions->row->id , 'split' => 1 ]);
				$actions->append("<a href='$copynew' class='btn btn-xs btn-primary'>拆单</a>&nbsp;");

				if( in_array( $actions->row->state , [ 2 , 3 , 4 ] ) ) {
					$confirmUrl = route('admin.order.sendconfirm' , ['id' => $actions->row->id ]);
					$actions->append("<a data-href='$confirmUrl' class='btn btn-xs btn-primary order-sendconfirm'>发送确认</a>&nbsp;");
				}
			});
		});
	}

	/**
	 * 接单
	 */
	public function deal( $id , Request $request ) {
		$user = auth()->guard('admin')->user();
		$row = Order::where('id' , $id )->where('state' , 0 )->update([
				'admin_id' => $user->id ,
				'state' => 1
		]);
		if( $row > 0 ) {
			return response()->json( ['errcode' => 0 , 'msg' => '接单成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '接单失败']) ;
	}

	public function importexcel( $id ) {
		$order = Order::findOrFail( $id );
		session( ['previous' => \URL::previous() ]);
		return Admin::content(function( Content $content ){
			$content->header('导入运单');

			$content->description('导入运单');

			$content->body($this->form());

		});
	}

	public function saveexcel( $id ) {
		$order = Order::findOrFail( $id );
		$form = $this->form();
		$data = request()->all();
		$file = $form->builder()->field('excel')->prepare( data_get( $data , 'excel')) ;
		$excel = storage_path( 'app' ) . DIRECTORY_SEPARATOR . $file ;

		include_once app_path('Support/PHPExcel.php');

		try {
			$objPHPExcel = \PHPExcel_IOFactory::load( $excel );
			$sheet = $objPHPExcel->setActiveSheetIndex(0);
			$arr = array();
			foreach($sheet->getRowIterator() AS $row) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$data =array();
				foreach($cellIterator AS $key => $cell) {
					$data[] = trim($cell->getValue());
				}
				$arr[] =$data;
			}
			//1 处理公司
			$companyName = $arr[4][5];
			$company = Company::firstOrCreate([
				'name' => trim( $companyName )
			] , ['short_name' => trim( $companyName ) ] );
			$company_id = $company->id ;

			//2 处理货轮
			$_data =  explode("/", trim( $arr[6][5] ) );
			$shipName = trim( $_data[0] ) ;
			$ship = Ship::firstOrCreate( [
					'company_id' => $company_id ,
					'name' => $shipName
			] , [

			]);

			$startTime = data_get( $arr[7] , 5 , '' );
			if( $startTime ) {
				$startTime = $this->excel_to_date( $startTime );
				$s['start_time'] = date('Y-m-d H:i:s' , $startTime );
			} else {
				$startTime = 0 ;
				$s['start_time'] = null;
			}
			$endTime = data_get( $arr[8] , 5 , '' );
			if( $endTime ) {
				$endTime = $this->excel_to_date( $endTime );
				$s['end_time'] = date('Y-m-d H:i:s' , $endTime );
			} else {
				$endTime = 0 ;
				$s['end_time'] = null ;
			}

			$s["rebate"] =  floatval( data_get( $arr[11] , 5 , 0.00 ) );
			$s['rebate_status'] = 0 ;
			//封条号
			$s["seal_num"] =  data_get( $arr[7] , 3 , '' );
			//起运地
			$s['departure'] = $arr[5][1];
			//目的地
			$s['destination'] = $arr[8][1];

			$s['owner'] = data_get( $arr[8] , 3 );

			//处理航次
			$s['voyage'] = data_get( $_data , 1 );
			$flight = Flight::firstOrCreate( [
					'no' => $s['voyage'] ,
					'ship_id' => $ship->id ,

			] , [
				'from' => $s['departure'] ,
				'to' => $s['destination'] ,
				'from_time' => $startTime ,
				'to_time' => $endTime ,
			]);
			$s['barge_time'] = "" ;
			if( trim( data_get( $arr[5] , 5 , '')) ) {
				$bargeTime = $this->excel_to_date( trim( data_get( $arr[5] , 5 , '')) );
				$bargeTime = date('Y-m-d' , $bargeTime );
				$s['barge_time'] = $bargeTime ;
			}
			//运单号
			$s['waybill'] =$arr[5][3];
			$s['trailer_cost'] = $arr[10][1]?$arr[10][1]:0;
			$s['ship_cost'] = $arr[10][3]?$arr[10][3]:0;
			$s['other_cost'] = $arr[10][5]?$arr[10][5]:0;
			$s['costinfo'] = $arr[11][1]?$arr[11][1]:0;
			//船公司
			$s["company_id"] = $company_id ;
			//船
			$s["ship_id"] = $ship->id ;
			//航次


			//柜号信息
			$cc  = explode("x",$arr[3][5]);
			$s['cabinet'] =$cc[1];
			$s['cabinet_num'] =$cc[0];
			//更新商品的柜数和柜型
			$boxType = config('global.box_type') ;
			$boxKey = array_search( $cc[1],  $boxType );
			$order->goods->update([
					'box_num' => $cc[0] ,
					'box_type' => $boxKey
			]);


			/**
			$startTime = data_get( $arr[7] , 5 , '' );
			$startTime = $this->excel_to_date( $startTime );
			$s['start_time'] = date('Y-m-d H:i:s' , $startTime );
			$endTime = data_get( $arr[8] , 5 , '' );
			$endTime = $this->excel_to_date( $endTime );
			$s['end_time'] = date('Y-m-d H:i:s' , $endTime );
			**/
			$s["rebate"] =  floatval( data_get( $arr[11] , 5 , 0.00 ) );
			$s['rebate_status'] = 0 ;
			//封条号
			$s["seal_num"] =  data_get( $arr[7] , 3 , '' );

			//运输条款
			$ord["trains_case"] =  $arr[2][5]?$arr[2][5]:"";
			//柜号
			$s["cabinet_no"] =  $arr[6][3]?$arr[6][3]:"";
			//别人的系统订单单号
			$s['order_sn'] =preg_replace('/[^\d|^A-Z]/i',"",$arr[13][0]);
			if( empty( $s['order_sn'] ) ) {
				$s['order_sn'] =preg_replace('/[^\d|^A-Z]/i',"",$arr[13][1]);
			}
			$s['file'] = $file ;
			$s['state'] = 2 ;
			$row = $order->update( $s );
			if($row){
				//写入相关的信息
				/**
				$recUser = User::where('rec_id' , $order->user_id )->first();
				if( $recUser ) {
					//写入推广赠送
					$reward = new Reward();
					$reward->user_id = $recUser->id ;
					$reward->cash = $cabinet_num * 50 ;
					$reward->order_id = $order->id ;
					$reward->status = 0 ;
					$reward->save();
				}**/
				//更新成功

				admin_toastr('导入完成' );
		        return redirect( session( 'previous') );
			}else{
				//更新失败
				admin_toastr('导入失败' , 'fail' );
				return redirect()->back();
			}
		} catch (Exception $e ) {
			admin_toastr('导入失败' , 'error' );
			return redirect()->back();
		}

	}

	public function sendconfirm( $id ) {
		//发送短信通知
		$order = Order::findOrFail( $id );
		//SMS_66675274 尊敬的客户您好！您的订舱已确认，运单号：${order}，您可以登陆富裕通网上平台在“我的订单”中查看详细订单信息。
		$sms = new Sms( config( 'global.sms_app_id') , config( 'global.sms_app_key') , config( 'global.sms_sign') ) ;
		$tpl = config('global.sms_order_confirm') ;
		if( trim( $order->waybill ) == '' ) {
			return response()->json( ['errcode' => 10001 , 'msg' => '运单号还没有填写']) ;
		}
		$param = ['order' => "{$order->waybill}" ] ;
		$user = User::where('id' , $order->user_id )->first();
		$result = $sms->sdkSend( $user->name , $param , $tpl );
		if( $result ) {
			return response()->json( ['errcode' => 0 , 'msg' => '发送成功']) ;
		}
		return response()->json( ['errcode' => 10002 , 'msg' => '发送失败']) ;
	}


	protected function excel_to_date($days, $time=false){
		$myDateStr ="";
		if(is_numeric($days)){
			$jd = gregoriantojd(1, 1, 1970);
			$gregorian = jdtogregorian($jd+intval($days)-25569);
			$myDate = explode('/',$gregorian);
			$myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)
			."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)
			."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)
			.($time?" 00:00:00":'');
		}else{
			$myDateStr = $days;
		}
		return strtotime($myDateStr);
	}

	/**
	 * 作废订单
	 * @param unknown $id
	 * @param Request $request
	 */
	public function destory( $id , Request $request ) {
		$user = auth()->guard('admin')->user();
		$row = Order::where('id' , $id )->whereIn('state' , [ 0 , 1 , 2 ] )->where('state' , '<>' , 9 )->update([
				'admin_id' => $user->id ,
				'state' => 9
		]);
		if( $row > 0 ) {
			return response()->json( ['errcode' => 0 , 'msg' => '作废成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '作废失败']) ;
	}

	public function show( $id , Request $request ) {
		Admin::script( view('admin.script.orderlist')->render() );
		return Admin::content( function( Content $content ) use( $id , $request ) {
			$content->header('订单管理');

			$content->description('详情');

			$order = Order::with('goods' , 'sender' , 'entrust' , 'recevier' )->findOrFail( $id );
			$back = \URL::previous() ;
			if( $request->getUri() == $back ) {
				$back = route('order.index');
			}
			$content->row( view('admin.order.ophandel' , ['id' => $id , 'order' => $order , 'back' => $back ] ) );
			$headers = ['Id', 'Email', 'Name', 'Company', 'Last Login', 'Status'];
			$baseInfo = view('admin.order.baseinfo' , ['order' => $order ] )->render();
			$content->row( new Box( '基本信息' , $baseInfo ) );
			$entrustInfo = view('admin.order.entrust' , ['entrust' => $order->entrust] )->render();

			$goodsInfo = view('admin.order.goods' , ['goods' => $order->goods] )->render();
			$content->row( new Box('货物信息' , $goodsInfo )  );

			$content->row( new Box('托运人信息' , $entrustInfo )  );
			$senderInfo = view('admin.order.sender' , ['sender' => $order->sender] )->render();
			$content->row( new Box('发货人信息' , $senderInfo )  );

			$recevierInfo = view('admin.order.recevier' , ['recevier' => $order->recevier] )->render();
			$content->row( new Box('收货人信息' , $recevierInfo )  );
		});
	}

	public function finished( $id ) {
		$user = auth()->guard('admin')->user();
		$order = Order::findOrFail( $id );
		$row = Order::where('id' , $id )->where('is_finished' , 0 )->update([
				'is_finished' => 1
		]);
		if( $row > 0 ) {
			return response()->json( ['errcode' => 0 , 'msg' => '设置为已追踪完成']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '设置为已追踪失败']) ;
	}

	/**
	 * 出货
	 * @param unknown $id
	 */
	public function send( $id ) {
		$user = auth()->guard('admin')->user();
		$order = Order::findOrFail( $id );
		$row = Order::where('id' , $id )->where('state' , 2 )->update([
				'admin_id' => $user->id ,
				'state' => 3
		]);
		if( $row > 0 ) {
			//获取推广关系
			$user = User::where('id' , $order->user_id )->first();
			if( $user->rec_id ) {
				$recUser = User::findOrFail( $user->rec_id );
				//写入订单赠送
				$reward = new Reward();
				$reward->user_id =  $recUser->id ;
				$reward->cash = 50 * $order->goods->box_num ;
				$reward->status = 0 ;
				$reward->order_id = $order->id ;
				$reward->expect = date('Y-m-d H:i:s' , strtotime( $order->end_time ) + 86400 * 7 ) ;
				$reward->save();
			}

			return response()->json( ['errcode' => 0 , 'msg' => '更改为出货成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '更改为出货失败']) ;
	}

	public function back( $id ) {
		$user = auth()->guard('admin')->user();
		$row = Order::where('id' , $id )->whereIn('state' , [0 , 1 , 2 ] )->update([
				'admin_id' => $user->id ,
				'state' => 8
		]);
		if( $row > 0 ) {
			return response()->json( ['errcode' => 0 , 'msg' => '更改为返回修改成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '更改为返回修改失败']) ;
	}

	/**
	 * 收款
	 */
	public function take( $id ) {
		$user = auth()->guard('admin')->user();
		$order = Order::findOrFail( $id );
		$row = Order::where('id' , $id )->where('state' , 3 )->update([
				'admin_id' => $user->id ,
				'state' => 4 ,
				'rebate_status' => 1 //无论有没有返利 都把返利状态改为执行
		]);
		if( $row > 0 ) {
			//写入订单赠送
			if( $order->rebate && $order->rebate_status == 0 ) {
				//如果有返利并且 返利状态为0 才进行返利
				$orderUser = User::findOrFail( $order->user_id );
				$refund = new Refund();
				$refund->user_id = $order->user_id ;
				$refund->cash = $order->rebate ;
				$refund->status = 1 ;
				$refund->order_id = $order->id ;
				$refund->save();

				$finance = new Finance() ;
				$finance->user_id = $orderUser->id ;
				$finance->cash = $order->rebate ;
				$finance->act = 'in' ;
				$finance->orgin_cash = $orderUser->money ;
				$finance->result_cash = $orderUser->money + $order->rebate  ;
                $finance->type = "订单返利，订单编号为:{$order->id}运单号为:" . $order->waybill ;
				$finance->target_id = $refund->id ;
				$finance->save();
				User::where('id' , $orderUser->id )->where('money' , $orderUser->money )->update(['money' => $finance->result_cash ] ) ;

			}
			return response()->json( ['errcode' => 0 , 'msg' => '更改为付款成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '更改为付款失败']) ;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		return Admin::form( Order::class, function (Form $form) {
			$form->setAction( request()->url() );
			$form->display('id', 'ID');
			$form->file('excel' , '订单确认涵' );
			$form->tools( function( $tool ) {
				$tool->disableListButton();
			});
		});
	}


	public function change() {
		return Admin::content(function (Content $content) {

			$content->header('订单更正管理');
			$content->description('列表');

			$content->body($this->changegrid());
		});
	}

	protected function changegrid() {
		return Admin::grid( OrderChangeLog::class , function( Grid $grid ){
			$grid->model()->orderBy('id' , 'desc' );
			$grid->id('id');
			$grid->column('user.name' , '用户手机');
			$grid->column('admin.username' , '管理员');
			$grid->status('状态')->display( function( $v ){
				return data_get( config('global.change_status' ) , $v );
			});
			$grid->disableBatchDeletion();
			$grid->disableCreation();
			$grid->disableExport();
			$grid->created_at('申请时间');
			$grid->actions( function( $action ) {
				$action->disableDelete();
				$action->disableEdit();
				$showUrl = route('admin.orderchange.show' , ['id' => $action->row->id ] );
				$action->append("<a href='{$showUrl}' class='btn btn-xs btn-primary order-deal'>详情</a>&nbsp;");
			});
		} );
	}

	public function changeshow( $id ) {
		return Admin::content( function( Content $content ) use( $id ) {
			$changeLog = OrderChangeLog::findOrFail( $id );
			$content->header('订单变更');
			$content->description('订单编号：' . $changeLog->order_id );

			$content->row( view('admin.order.show' , ['log' => $changeLog ])->render() ) ;

			$log = json_decode( $changeLog->content );
			$headers = ['变更值', '变更前', '变更后' ];
			$rows = [] ;
			foreach( $log as $k => $val ) {
				if( $k == 'order.shipment' ) {

				}
				switch( $k ) {
					case 'order.shipment':
					case 'order.destinationport' :
						$from = Port::find( $val->from );
						$to = Port::find( $val->to );
						$rows[] = [
								trans( $k ) , $from->name , $to->name
						] ;
						break ;
					case 'order.company_id':
						$from = Company::find( $val->from );
						$to = Company::find( $val->to );
						$rows[] = [
								trans( $k ) , data_get( $from , 'name' ) , data_get( $to , 'name' )
						] ;
						break ;
					case 'order.goods_kind':
						$rows[] = [
						trans( $k ) ,
						data_get( config('global.goods_kind') , $val->from )
						,
						data_get( config('global.goods_kind') , $val->to )
						] ;
						break ;
					case 'sender.load_date':
						if( str_limit( $val->from , 10 , '' ) != $val->to ) {
							$rows[] = [
									trans( $k ) ,
									str_limit( $val->from , 10 , '' )
									,
									$val->to
							] ;
						}
						break ;
					default:
						$rows[] = [
						trans( $k ) , $val->from , $val->to
						] ;
				}

			}
			$content->row( (new Box('变更详情', new Table($headers, $rows)))->style('success')->solid() ) ;
			if( $changeLog->status == 0 ) {
				$allowUrl = route('order.change.allow' , ['id' => $id ] );
				$disallowUrl = route('order.change.disallow' , ['id' => $id ] );
				$content->row('<a data-href="'. $allowUrl .'" class="btn btn-success allow">同意变更</a>&nbsp;<a data-href="'. $disallowUrl .'" class="btn btn-danger disallow">不同意变更</a>');
				$content->row("&nbsp;");
			}
			$order = Order::with('goods' , 'sender' , 'entrust' , 'recevier' )->findOrFail( $changeLog->order_id );
			$baseInfo = view('admin.order.baseinfo' , ['order' => $order ] )->render();
			$content->row( new Box( '基本信息' , $baseInfo ) );
			$entrustInfo = view('admin.order.entrust' , ['entrust' => $order->entrust] )->render();
			$content->row( new Box('托运人信息' , $entrustInfo )  );
			$senderInfo = view('admin.order.sender' , ['sender' => $order->sender] )->render();
			$content->row( new Box('发货人信息' , $senderInfo )  );

			$recevierInfo = view('admin.order.recevier' , ['recevier' => $order->recevier] )->render();
			$content->row( new Box('收货人信息' , $recevierInfo )  );
			Admin::script( view('admin.script.change')->render() );

		});
	}

	public function allowchange( $id ) {
		$user = auth()->guard('admin')->user();
		$row = OrderChangeLog::where('id' , $id )->where('status' , 0 )->update([
				'admin_id' => $user->id ,
				'status' => 1
		]);
		if( $row > 0 ) {
			//并且修改对应的信息
			$changeLog = OrderChangeLog::findOrFail( $id );
			$log = json_decode( $changeLog->content );
			$order = Order::with('goods' , 'entrust' , 'sender' , 'recevier')->findOrFail( $changeLog->order_id );
			foreach( $log as $k => $val ) {
				list( $type , $key ) = explode( '.' , $k );
				switch( $type ) {
					case 'order':
						$order->$key = data_get( $val , 'to' ) ;
						break ;
					case 'goods':
						$order->goods->$key = data_get( $val , 'to' ) ;
						break ;
					case 'entrust':
						$order->entrust->$key = data_get( $val , 'to' ) ;
						break;
					case 'sender':
						$order->sender->$key = data_get( $val , 'to' ) ;
						break ;
					case 'recevier':
						$order->recevier->$key = data_get( $val , 'to' ) ;
						break ;
				}

			}
			if( $order->save() && $order->goods->save() && $order->entrust->save()
					&& $order->sender->save() && $order->recevier->save() ) {
						return response()->json( ['errcode' => 0 , 'msg' => '通过成功']) ;
					}

			return response()->json( ['errcode' => 10002 , 'msg' => '通过成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '通过失败']) ;
	}

	public function disallowchange( $id ) {
		$user = auth()->guard('admin')->user();
		$row = OrderChangeLog::where('id' , $id )->where('status' , 0 )->update([
				'admin_id' => $user->id ,
				'status' => 2
		]);
		if( $row > 0 ) {
			return response()->json( ['errcode' => 0 , 'msg' => '不通过成功']) ;
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '不通过失败']) ;
	}
}
