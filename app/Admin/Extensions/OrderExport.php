<?php
namespace App\Admin\Extensions ;


use Encore\Admin\Grid\Exporters\AbstractExporter;
use App\Models\FlightPortPrice;
use App\Models\Port;

class OrderExport extends AbstractExporter {
	
	public function export() {
		
		$this->grid->model()->with('company' , 'flight' );
		
		$data = $this->getData();
		include_once app_path('Support/PHPExcel.php');
		$excelObj = new \PHPExcel();
		$excelObj->getProperties()->setTitle( '后台订单导出' )
		->setSubject('订单导出')
		->setCompany( config('app.name') );
		$sheet = $excelObj->setActiveSheetIndex( 0 );
		$ports = Port::pluck('name' , 'id');
		
		$sheet->mergeCells('A1:L1');
		$sheet->setCellValue( 'A1' , '厦门富裕通物流有限公司' )->getStyle('A1')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A1' )->getFont()->setBold( true )->setSize( 20 ) ;
		

		$sheet->mergeCells('A2:L2');
		$sheet->setCellValue( 'A2' , '福建省漳州港开发区招商大道融和国际商务中心403-404室  电话:0596-6856110 传真:86-4008266163-01121' )->getStyle('A1')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A2' )->getFont()->setBold( true )->setSize( 16 ) ;
		
		$sheet->mergeCells('A3:L3');
		$sheet->setCellValue( 'A3' , '订单汇总表' )->getStyle('A1')->getAlignment()
		->setHorizontal( \PHPExcel_Style_Alignment::VERTICAL_CENTER)
		->setVertical( \PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$sheet->getStyle( 'A3' )->getFont()->setBold( true )->setSize( 20 ) ;
		$i = 4 ;
		$sheet->setCellValue( 'A' . $i , '船公司名称' ) ;
		$sheet->setCellValue( 'B' . $i , '船名称' ) ;
		$sheet->setCellValue( 'C' . $i , '航次' ) ;
		$sheet->setCellValue( 'D' . $i , '运单号' ) ;
		$sheet->setCellValue( 'E' . $i , 'ID' ) ;
		$sheet->setCellValue( 'F' . $i , '订单号' ) ;
		//获取这个航次的大船信息
		$sheet->setCellValue( 'G' . $i , '预计开船' ) ;
		$sheet->setCellValue( 'H' . $i , '实际开船' ) ;
		//获取这个航次的最后编辑时间
		$sheet->setCellValue( 'I' . $i , '航次更新时间' ) ;
		//获取这个船的航线的时间
		$sheet->setCellValue( 'J' . $i , '各港到达时间' ) ;
		$sheet->setCellValue( 'K' . $i , '派送情况' ) ;
		$sheet->setCellValue( 'L' . $i , '备注' ) ;
		
		$i++ ;
		foreach( $data as $val ) {
			$sheet->setCellValue( 'A' . $i , data_get( data_get( $val , 'company') , 'name' ) ) ;
			$sheet->setCellValue( 'B' . $i , data_get( data_get( $val , 'ship') , 'name' ) ) ;
			$sheet->setCellValue( 'C' . $i , data_get( $val , 'voyage' ) ) ;
			$waybill = data_get( $val , 'waybill' ) ;
			if( preg_match("/^\d{10,}$/", $waybill ) ) {
				$waybill = "'" . $waybill ;
			}
			$sheet->setCellValue( 'D' . $i , $waybill ) ;
			$sheet->setCellValue( 'E' . $i , data_get( $val , 'id' ) ) ;
			$sheet->setCellValue( 'F' . $i , data_get( $val , 'order_sn' ) ) ;
			//获取这个航次的大船信息
			
			//预计开船 以驳船时间为准
			$sheet->setCellValue( 'G' . $i , data_get( $val , 'barge_plan_time' ) ) ;
			//实际开船 以驳船时间为准
			$sheet->setCellValue( 'H' . $i , data_get( $val , 'barge_time' ) ) ;
			
			//获取这个航次的最后编辑时间
			$sheet->setCellValue( 'I' . $i , data_get( data_get( $val , 'flight' ) , 'updated_at' ) ) ;
			//获取这个船的航线的时间
			$fromA = data_get( $val , 'shipment' );
			$fromB = data_get( $val , 'barge_port') ;
			$toB = data_get( $val , 'barge_to_port') ;
			$toA = data_get( $val , 'destinationport') ;
			$query = FlightPortPrice::where('flight_id' , data_get( data_get( $val , 'flight' ) , 'id' )) ;
			if( $fromB ) {
				//如果有起运驳船
				$query->where('from_port_id' , $fromA )->where('from_barge_port_id' , $fromB );
			} else {
				$query->where('from_port_id' , $fromA );
			}
			if( $toB ) {
				//如果有到港驳船
				$query->where('to_port_id' , $toA )->where('to_barge_port_id' , $toB );
			} else {
				$query->where('to_port_id' , $toA );
			}
			$flightPrice = $query->first();
			if( $flightPrice ) {
				$cellString = "";
				$cellString .= data_get( $ports , data_get( $flightPrice , 'from_port_id') ) .':'. data_get( $flightPrice , 'from_port_leave_time') . "\r\n" ;
				if( $flightPrice->form_port_id != $flightPrice->from_barge_port_id ) {
					$cellString .= data_get( $ports , data_get( $flightPrice , 'from_barge_port_id') ) .':'. data_get( $flightPrice , 'from_barge_port_arrive_time') . "\r\n" ;
				}
				if( $flightPrice->to_port_id != $flightPrice->to_barge_port_id ) {
					$cellString .= data_get( $ports , data_get( $flightPrice , 'to_barge_port_id') ) .':'. data_get( $flightPrice , 'to_port_leave_time')  . "\r\n" ;
				}
				$cellString .= data_get( $ports , data_get( $flightPrice , 'to_port_id') ) .':'. data_get( $flightPrice , 'to_barge_port_arrive_time') ;
				$sheet->setCellValue( 'J' . $i , $cellString ) ;
			}
			$sheet->setCellValue( 'K' . $i , data_get( $val , 'barge_to_remark' ) ) ;
			$sheet->setCellValue( 'L' . $i , data_get( $val , 'remark' ) ) ;
			$i++ ;
		}
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. date('Y-m-d H:i:s') .'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter( $excelObj , 'Excel5');
		$objWriter->save('php://output');
	}
}