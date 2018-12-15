<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Port;
use App\Models\OrderGoods;
use Illuminate\Support\Facades\DB;
use App\Models\Order ;
use App\Models\OrderEntrust;
use App\Models\OrderSender;
use App\Models\OrderRecevier;
use Sherry\Cms\Models\Category;
use Sherry\Cms\Models\Advertisement;
use Sherry\Cms\Models\Posts;
use Sherry\Cms\Models\Single;
use App\Models\UserBank;
use App\Models\Refund;
use App\Models\Reward;
use App\User;

class MemberController extends Controller {
	
	/**
	 * 我的会员中心
	 */
	public function index() {
		
		return view('member.index') ;
	}
	
	
	public function order( Request $request ) {
		
		$user = auth()->guard()->user();
		$query = Order::with('fromport' , 'toport' , 'goods' )->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('state' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$query->where('state' , '<' , 10 );
		$order = $query->paginate( 10 );
		$data = [
				'order' => $order ,
				'leftMenu' => 'order' ,
		] ;
		return view('member.order' , $data );
	}
	
	public function orderdetail( $id ) {
		$order = Order::findOrFail( $id );
		$user = auth()->guard('web')->user();
		if( $order->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限');
		}
		$data = [
				'order' => $order ,
				'leftMenu' => 'order' ,
				'entrust' => $order->entrust ,
				'sender' => $order->sender ,
				'recevier' => $order->recevier ,
				'goods' => $order->goods ,
		] ;
		return view('member.orderdetail' , $data ) ;
	}
	
	
	public function exportgoods( Request $request ) {
		$from = $request->input('start');
		$to = $request->input('end') ;
		$orderState = $request->input('order_state');
		$user = auth()->guard('web')->user();
		$query = Order::with('goods' , 'sender' , 'entrust' , 'recevier' , 'fromport' , 'toport') ;
		if( $from ) {
			$query = $query ->where( 'created_at' , '>=' , date('Y-m-d 00:00:00' , strtotime( $from ) ) ) ;
		}
		if( $to ) {
			$query = $query->where('created_at' , '<=' ,  date('Y-m-d 23:59:59' , strtotime( $to ) ) ) ;
		}
		//->whereBetween('created_at' ,[ $from . ' 00:00:00' , $to . ' 00:00:00' ] )
		$query->where('waybill' , '<>' , '' )
		->where('user_id' , $user->id );
		if( $orderState  ) {
			$query = $query->where('state' , $orderState ) ;	
		} else {
			$query = $query->whereIn('state' , [2,3,4]) ;
		}
		
		$result = $query->take( 200 )->get();
		include_once app_path('Support/PHPExcel.php');
		$excelObj = new \PHPExcel();
		$excelObj->getProperties()->setTitle( '出货记录表' )
		->setSubject('订单导出')
		->setCompany( config('app.name') );
		$sheet = $excelObj->setActiveSheetIndex( 0 );
		$sheet->getColumnDimension('A')->setWidth(18.5);
		$sheet->getColumnDimension('B')->setWidth(18.5);
		$sheet->getColumnDimension('C')->setWidth(18.5);
		$sheet->getColumnDimension('D')->setWidth(18.5);
		$sheet->getColumnDimension('E')->setWidth(18.5);
		$sheet->getColumnDimension('F')->setWidth(18.5);
		$sheet->getColumnDimension('G')->setWidth(18.5);
		$sheet->getColumnDimension('H')->setWidth(18.5);
		$sheet->getColumnDimension('I')->setWidth(18.5);
		$sheet->getColumnDimension('J')->setWidth(18.5);
		$sheet->getColumnDimension('K')->setWidth(18.5);
		$sheet->getDefaultRowDimension()->setRowHeight(12);
		$sheet->getDefaultRowDimension(1)->setRowHeight(30);
		$sheet->mergeCells('A1:K1');
		$sheet->setCellValue( 'A1' , '厦门富裕通物流有限公司' )->getStyle('A1')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A1' )->getFont()->setBold( true )->setSize( 20 ) ;
		
		$sheet->mergeCells('A2:K2');
		$sheet->setCellValue( 'A2' , '出货记录表' )->getStyle('A2')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A2' )->getFont()->setBold( true )->setSize( 14 ) ;
		
		$sheet->mergeCells('A3:K3');
		$sheet->setCellValue( 'A3' , '下单日期' . $from . '~' . $to  )->getStyle('A3')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A3' )->getFont()->setBold( true )->setSize( 14 ) ;
		
		$sheet->setCellValue( 'A4' , '订单编号' );
		$sheet->setCellValue( 'B4' , '下单日期' );
		$sheet->setCellValue( 'C4' , '装货日期' );
		$sheet->setCellValue( 'D4' , '开船日期' );
		$sheet->setCellValue( 'E4' , '到达日期' );
		$sheet->setCellValue( 'F4' , '装货地址' );
		$sheet->setCellValue( 'G4' , '收货地址' );
		$sheet->setCellValue( 'H4' , '运单号' );
		$sheet->setCellValue( 'I4' , '箱号' );
		$sheet->setCellValue( 'J4' , '船公司' );
		$sheet->setCellValue( 'K4' , '收货人' );
		
		$i = 5 ;
		foreach( $result as $val ) {
			$no = explode( ',' , $val->cabinet_no );
			if( isset( $no ) && is_array( $no ) && !empty( $no ) ) {
				foreach( $no as $v ) {
					$sheet->setCellValue( 'A' . $i  , $val->order_sn );
					$sheet->setCellValue( 'B' . $i  , str_limit( $val->created_at , 10 , '' ) );
					$loadDate = str_limit( data_get( $val->sender , 'load_date') , 10 , '' ) ;
					$loadDate = '0000-00-00' == $loadDate ? '' : $loadDate ;
					$sheet->setCellValue( 'C' . $i  , $loadDate );
					$startTime = str_limit( $val->start_time , 10 , '' );
					$startTime = '0000-00-00' == $startTime ? '' : $startTime ;
					$sheet->setCellValue( 'D' . $i  , $startTime  );
					$endTime = str_limit( $val->end_time , 10 , '' );
					$endTime = '0000-00-00' == $endTime ? '' : $endTime ;
					$sheet->setCellValue( 'E' . $i  , $endTime );
					$sheet->setCellValue( 'F' . $i  , data_get( $val->sender , 'address' ) );
					$sheet->setCellValue( 'G' . $i  , data_get( $val->recevier , 'address' ) );
					$sheet->setCellValue( 'H' . $i  , $val->waybill )->getStyle( 'H' . $i )
					->getAlignment()
					->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$sheet->setCellValue( 'I' . $i  , $v );
					$sheet->setCellValue( 'J' . $i  , data_get( $val->company , 'name') );
					$sheet->setCellValue( 'K' . $i  , data_get( $val->recevier , 'name' ) );
					$i++ ;
				}
			}
			
		}
		$i++ ;
		$sheet->mergeCells("A$i:K$i");
		$sheet->setCellValue( "A$i" , '厦门富裕通物流有限公司' )->getStyle('A' . $i )->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( "A$i" )->getFont()->setBold( true )->setSize( 18 ) ;
		$i++ ;
		$sheet->mergeCells("A$i:K$i");
		$sheet->setCellValue( "A$i" , date('Y年m月d日' , time() )  )->getStyle( 'A' . $i )->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( "A$i" )->getFont()->setBold( true )->setSize( 14 ) ;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. date('Y-m-d H:i:s') .'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter( $excelObj , 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function orderexport( $id ) {
		$order = Order::with('goods' , 'sender' , 'entrust' , 'recevier' , 'fromport' , 'toport')->findOrFail( $id );
		$user = auth()->guard('web')->user();
		if( $order->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限');
		}
		//绘制表格
		include_once app_path('Support/PHPExcel.php');
		$excelObj = new \PHPExcel();
		$excelObj->getProperties()->setTitle( $order->waybill )
		->setSubject('订单导出')
		->setCompany( config('app.name') );
		$sheet = $excelObj->setActiveSheetIndex( 0 );
		
		$sheet->getColumnDimension('A')->setWidth(18.5);
		$sheet->getColumnDimension('B')->setWidth(18.5);
		$sheet->getColumnDimension('C')->setWidth(18.5);
		$sheet->getColumnDimension('D')->setWidth(18.5);
		$sheet->getColumnDimension('E')->setWidth(18.5);
		$sheet->getColumnDimension('F')->setWidth(18.5);
		$sheet->getDefaultRowDimension()->setRowHeight(12);
		$sheet->getDefaultRowDimension(1)->setRowHeight(30);
		
		$black = [
				'A3' , 'A4' , 'A5' , 'A6' , 'A7' , 'A8' , 'A9' , 'A10' , 'A11' , 'A12' , 'A13' , 'A14' ,
				'C6' , 'C7' , 'C8' , 'C9' , 'C11' ,
				'E3' , 'E4' , 'E5' , 'E6' , 'E7' , 'E8' , 'E9' , 'E10' , 'E11' , 'E12' , 'E14' ,
				'F14'
				
		] ;
		
		foreach( $black as $v  ) {
			$sheet->getStyle( $v )->getAlignment()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_LEFT )
			->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$sheet->getStyle( $v )->getFont()->setName("黑体") ;
		}
		
		$red = [
			'B3' , 'B4' , 'B5' , 'B6' , 'B7' , 'B8' , 'B9' , 'B11' , 'B12' , 'B14' ,
			'D6' , 'D7' , 'D8' , 'D9' , 'D11' ,
			'F3' , 'F4' , 'F5' , 'F6' , 'F7' , 'F8' , 'F9' , 'F11' , 'F12' , 'F14'
		] ;
		foreach( $red as $v  ) {
			$sheet->getStyle( $v )->getAlignment()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT )
			->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$sheet->getStyle( $v )->getFont()->setName("黑体")->getColor()->setARGB( \PHPExcel_Style_Color::COLOR_RED);
		}
		$center = [
				'B3' , 'B4' , 'B5' ,
		] ;
		foreach( $center as $v  ) {
			$sheet->getStyle( $v )->getAlignment()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_CENTER )
			->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		}
		$left = [
				'B6' , 'B7' , 'B8' , 'B9' ,
				'D6' , 'D7' , 'D8' , 'D9'
		] ;
		foreach( $left as $v  ) {
			$sheet->getStyle( $v )->getAlignment()->setHorizontal( \PHPExcel_Style_Alignment::HORIZONTAL_LEFT )
			->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		}
		
		$sheet->mergeCells('A1:F1');
		$sheet->setCellValue( 'A1' , '厦门富裕通物流有限公司' )->getStyle('A1')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A1' )->getFont()->setBold( true )->setSize( 20 ) ;
		
		$sheet->mergeCells('A2:F2') ;
		$sheet->setCellValue('A2' , '订舱确认函' );
		$sheet->getStyle( 'A2' )->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		
		$sheet->setCellValue('A3' , '托运人') ;
		$sheet->mergeCells('B3:D3');
		$sheet->setCellValue('B3' , data_get( $order->entrust , 'name') ) ;
		$sheet->setCellValue('E3' , '运输条款') ;
		$sheet->setCellValue('F3' , data_get( config('global.transport_protocol') , $order->transport_protocol )) ;
		//发货人
		$sheet->setCellValue('A4' , '发货人') ;
		$sheet->mergeCells('B4:D4');
		$sheet->setCellValue('B4' , data_get( $order->sender , 'name') . data_get( $order->sender , 'mobile') ) ;
		//柜型/柜量
		$sheet->setCellValue('E4' , '柜量/柜型') ;
		$sheet->setCellValue('F4' , data_get( $order->goods , 'box_num' ) . 'X' . data_get( config('global.box_type') , data_get( $order->goods , 'box_type') , '') ) ;
		
		//收货人
		$sheet->setCellValue('A5' , '收货人') ;
		$sheet->mergeCells('B5:D5');
		$sheet->setCellValue('B5' , data_get( $order->recevier , 'name') . data_get( $order->recevier , 'mobile') ) ;
		
		//船公司
		$sheet->setCellValue('E5' , '船公司') ;
		$sheet->setCellValue('F5' , data_get( $order->company , 'name' ) ) ;
		//起运地
		$sheet->setCellValue('A6' , '起运地') ;
		$sheet->setCellValue('B6' , data_get( $order->sender , 'address' ) ) ;
		//运单号
		$sheet->setCellValue('C6' , '运单号') ;
		$sheet->setCellValue('D6' , $order->waybill );
		$sheet->setCellValue('E6' , '预计驳船日期') ;
		$sheet->setCellValue('E6' , '预计驳船日期') ;
		$sheet->setCellValue('F6' , $order->barge_time );
		
		$sheet->setCellValue('A7' , '起运港' );
		$sheet->setCellValue('B7' , $order->fromport->name );
		$sheet->setCellValue('C7' , '柜号' );
		$sheet->setCellValue('D7' , $order->cabinet_no );
		$sheet->setCellValue('E7' , '大船船名/航次') ;
		$sheet->setCellValue('F7' , data_get( $order->ship , 'name' ) . '/' . data_get( $order , 'voyage' ) ) ;
		
		$sheet->setCellValue('A8' , '目的港' );
		$sheet->setCellValue('B8' , $order->toport->name );
		$sheet->setCellValue('C8' , '封条号' );
		$sheet->setCellValue('D8' , $order->seal_num );
		$sheet->setCellValue('E8' , '预计大船开航') ;
		if( $order->start_time && $order->start_time != '0000-00-00 00:00:00') {
			$time = strtotime( $order->start_time );
			$time = date('m月d日' , $time );
			$sheet->setCellValue('F8' , $time ) ;
		} else {
			$sheet->setCellValue('F8' , '' ) ;
		}
		
		$sheet->setCellValue('A9' , '目的地' );
		$sheet->setCellValue('B9' , data_get( $order->recevier , 'address' ) );
		$sheet->setCellValue('C9' , '货主' );
		//$sheet->setCellValue('D9' , '' );
		$sheet->setCellValue('D9' , data_get( $order , 'owner' , '' )  );
		$sheet->setCellValue('E9' , '预计到达') ;
		if( $order->end_time && $order->end_time != '0000-00-00 00:00:00') {
			$time = strtotime( $order->end_time );
			$time = date('m月d日' , $time );
			$sheet->setCellValue('F9' , $time ) ;
		} else {
			$sheet->setCellValue('F9' , '' ) ;
		}
		
		$sheet->mergeCells('A10:F10');
		$sheet->setCellValue( 'A10' , '费用明细' );
		
		$sheet->setCellValue('A11' , '拖车费' );
		$sheet->setCellValue('B11' , data_get( $order , 'trailer_cost' ) );
		$sheet->setCellValue('C11' , '海运费' );
		$sheet->setCellValue('D11' , data_get( $order , 'ship_cost' )  );
		$sheet->setCellValue('E11' , '其他费用') ;
		$sheet->setCellValue('F11' , data_get( $order , 'other_cost' ) ) ;
		
		$sheet->setCellValue('A12' , '其他费用包括' );
		$sheet->mergeCells('B12:D12');
		$sheet->setCellValue('B12' , data_get( $order , 'cost_info' ) );
		$sheet->setCellValue('E12' , '返利' );
		$sheet->setCellValue('F12' , data_get( $order , 'rebate') ) ;
		
		$sheet->mergeCells('A13:F13');
		$sheet->setCellValue('A13' , '备注：船期信息仅供参考，如发生船名、航次等船期信息变更将以实际运单信息为准。' );
		
		$sheet->setCellValue('A14' , '订单编号');
		$sheet->mergeCells('B14:C14');
		$sheet->setCellValue('B14' , data_get( $order , 'order_sn' ) );
		
		$sheet->setCellValue('E14' , '导出日期');
		$sheet->setCellValue('F14' , date('Y-m-d')) ;
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. date('Y-m-d H:i:s') .'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter( $excelObj , 'Excel5');
		$objWriter->save('php://output');
	}
	
	
	public function bank() {
		$user = auth()->guard()->user();
		$query = UserBank::where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$bank = $query->paginate( 10 );
		$data = [
				'bank' => $bank ,
				'leftMenu' => 'bank' ,
		] ;
		return view('member.bank' , $data );
	}
	
	public function bankcreate() {
		$user = auth()->guard()->user();
		$count = UserBank::where( 'user_id' , $user->id )->count();
		if( $count > 5 ) {
			return redirect()->back()->with('error' , '您添加的银行卡数已经达到最大上限！') ;
		}
		$data = [
				'leftMenu' => 'bank' ,
		];
		return view('member.bankcreate' , $data);
	}
	
	
	public function bankstore( Request $request ) {
		$user = auth()->guard()->user();
		$count = UserBank::where( 'user_id' , $user->id )->count();
		if( $count > 5 ) {
			return redirect()->back()->with('error' , '您添加的银行卡数已经达到最大上限！') ;
		}
		$bank = new UserBank();
		$bank->user_id = $user->id ;
		$bank->name = $request->input('name');
		$bank->card_no = $request->input('card_no') ;
		$bank->bank_id = $request->input('bank_id');
		if( $bank->save() ) {
			return redirect( route('member.bank') )->with('success' , '添加成功！') ;
		}
		return redirect()->back()->with('error' , '添加银行卡出错！') ;
	}
	
	public function bankedit( $id ) {
		$user = auth()->guard()->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限！') ;
		}
		$data = [
				'leftMenu' => 'bank' ,
				'bank' => $card ,
		];
		return view('member.bankedit' , $data);
	}
	
	public function bankupdate( $id , Request $request ) {
		$user = auth()->guard()->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限！') ;
		}
		$card->name = $request->input('name');
		$card->card_no = $request->input('card_no') ;
		$card->bank_id = $request->input('bank_id');
		if( $card->save() ) {
			return redirect( route('member.bank') )->with('success' , '修改成功！') ;
		}
		return redirect()->back()->with('error' , '修改银行卡出错！') ;
	}
	
	public function bankdrop( $id ) {
		$user = auth()->guard()->user();
		$card = UserBank::findOrFail( $id );
		if( $card->user_id != $user->id ) {
			return redirect()->back()->with('error' , '您没有权限！') ;
		}
		if( $bank->delete() ) {
			return redirect( route('member.bank') )->with('success' , '删除成功！') ;
		}
		return redirect()->back()->with('error' , '删除出错！') ;
	}
	
	

	/**
	 * 返利   自己下单返利
	 */
	public function refund( Request $request ) {
		$user = auth()->guard()->user();

		$query = Refund::with('order')->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('status' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'refund' ,
				'moeny' => $user->money
		] ;
		return view('member.refund' , $data );
	}
	
	/**
	 * 奖励
	 */
	public function reward(  Request $request  ) {
		$user = auth()->guard()->user();
		$query = Reward::with('order')->where( 'user_id' , $user->id )->orderBy('id' ,'desc');
		$status = $request->input('status');
		if( trim( $status ) != '' ) {
			$query->where('status' , $status );
		}
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'reward' ,
				'moeny' => $user->money
		] ;
		return view('member.reward' , $data );
	}
	
	public function recom( Request $request  ) {
		$user = auth()->guard()->user();
		$query = User::where( 'rec_id' , $user->id )->orderBy('id' ,'desc');
		$from = $request->input('from') ;
		if( $from ) {
			$query->where('created_at' , '>=' , $from . ' 00:00:00') ;
		}
		$to = $request->input('to') ;
		if( $to ) {
			$query->where('created_at' , '<=' , $to . ' 23:59:59') ;
		}
		$list = $query->paginate( 10 );
		$data = [
				'list' => $list ,
				'leftMenu' => 'recom' ,
				'moeny' => $user->money
		] ;
		return view('member.recom' , $data );
	}
	
	public function modpwd( Request $request ) {
		$oldPassword = $request->get('old_pwd');
		$newPassword = $request->get('pwd');
		
		if( auth()->guard('web')->getProvider()->getHasher()->check( $oldPassword , auth()->guard('web')->user()->getAuthPassword() ) ) {
			if( User::where('id' , auth()->guard('web')->user()->id )->update(['password' => bcrypt( $newPassword )]) ) {
				return response()->json(['errcode' => 0 , 'msg' => '修改成功' ]);
			}
			return response()->json(['errcode' => 10002 , 'msg' => '修改失败' ]);
		}
		return response()->json( ['errcode' => 10001 , 'msg' => '旧密码不正确' . $oldPassword . bcrypt( $oldPassword ) ]);
		
	}
	
	
	public function userinfo() {
		$user = auth()->guard()->user();
		$data = [
				'leftMenu' => 'userinfo' ,
				'money' => $user->money ,
				'user' => $user 
		] ;
		return view('member.userinfo' , $data );
	}
	
	
	public function userinfostore( Request $request ) {
		$user = auth()->guard('web')->user();
		$user->contact = $request->input('contact_name');
		$user->qq = $request->input('qq');
		if( $user->save() ) {
			return redirect( route('member.userinfo' ))->with('success' , '修改成功！') ;
		}
		return redirect( route('member.userinfo' ) )->back()->with('error' , '保存出错！') ;
	}
}