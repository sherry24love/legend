<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

        $router->get('/', 'HomeController@index');
    -   
    $router->group( ['middleware'=> [ 'admin.permission:check,basic'] ] ,  
    		function( $router ){
    			$router->resource('company', 'CompanyController') ;
    			$router->resource('ship' , 'ShipController');
    			$router->resource('city' , 'CityController');
    			$router->resource('port' , 'PortController');
    			$router->get('flight/create' , 'ShipController@flightcreate')->name('admin.flight.create');
    			$router->post('flight/create' , 'ShipController@flightimport')->name('admin.flight.create');
    			$router->get('flight/price/{id}' , 'FlightController@priceform' )->name('admin.flight.price');
    			$router->post('flight/price/{id}' , 'FlightController@pricestore' )->name('admin.flight.pricestore');
    			$router->resource('flight' , 'FlightController' , [
    					'names' => [
    							'index' => 'admin.flight.index' ,
    							'store' => 'admin.flight.store',
    							'update' => 'admin.flight.update' ,
    							'show' => 'admin.flight.show' ,
    					] ,
    			]) ;
    		});
    
    $router->group( ['middleware'=> [ 'admin.permission:check,basic'] ] ,
    		function( $router ){
    			$router->resource('flightprice' , 'FlightPriceController' );
    		}
    	);
    
    
    $router->group( ['middleware'=> [ 'admin.permission:check,order'] ] ,
    		function( $router ){
	
				$router->get('order/create' , 'OrderController@create')->name('order.create');
				$router->post('order' , 'OrderController@store')->name('order.create');
				$router->get('order' , 'OrderController@index')->name('order.index');
				$router->get('order/deal/{id}' , 'OrderController@deal')->name('admin.order.deal');
				$router->get('order/ship' , 'OrderController@ship')->name('order.ship');
				$router->get('order/flight' , 'OrderController@flight')->name('order.flight');
				$router->put('order/back/{id}' , 'OrderController@back' )->name('admin.order.back');
				
				$router->get('order/change' , 'OrderController@change' )->name('admin.order.change');
				$router->get('order/change/{id}' , 'OrderController@changeshow' )->name('admin.orderchange.show');
				$router->put('order/change/{id}/allow' , 'OrderController@allowchange' )->name('order.change.allow');
				$router->put('order/change/{id}/disallow' , 'OrderController@disallowchange' )->name('order.change.disallow');
				
				$router->get("order/new/{id}" , 'OrderController@copynew' )->name('order.copy');
				
				$router->delete( 'order/{id}' , 'OrderController@destory')->name('admin.order.delete' );
				$router->get('order/importexcel/{id}' , 'OrderController@importexcel' )->name('admin.order.import');
				$router->post('order/importexcel/{id}' , 'OrderController@saveexcel' )->name('admin.order.import');
				$router->post('order/finished/{id}' , 'OrderController@finished' )->name('admin.order.finished');
				$router->post('order/send/{id}' , 'OrderController@send' )->name('admin.order.send');
				$router->post('order/sendconfirm/{id}' , 'OrderController@sendconfirm' )->name('admin.order.sendconfirm');
				$router->post('order/take/{id}' , 'OrderController@take' )->name('admin.order.take');
				$router->resource('order' , 'OrderController' , [
						'names' => [
								'create' => 'order.create' ,
								'store' => 'order.create' ,
								'index' => 'order.index' ,
								'edit' => 'order.edit' ,
								'update' => 'order.edit' ,
								'show' => 'admin.order.show' ,
						]
						
				] );
    		});
	
	
    $router->group( ['middleware'=> [ 'admin.permission:check,finance'] ] ,
    		function( $router ){
				$router->get('finance' , 'FinanceController@index')->name('admin.finance');
				$router->get('withdraw' , 'WithdrawController@index')->name('admin.withdraw');
				$router->post('withdraw/{id}/ok' , 'WithdrawController@ok')->name('admin.withdraw.ok');
				$router->post('withdraw/{id}/fail' , 'WithdrawController@fail')->name('admin.withdraw.fail');
				$router->post('withdraw/{id}/deal' , 'WithdrawController@deal')->name('admin.withdraw.deal');
    		});
    
    $router->group( ['middleware'=> [ 'admin.permission:check,checkreg'] ] ,
    		function( $router ){
    			$router->get('checkreg' , 'MemberController@checkreg' )->name('admin.member.checkreg');
    });
    $router->group( ['middleware'=> [ 'admin.permission:check,member'] ] ,
    		function( $router ){
				$router->resource('member' , 'MemberController' , ['only' => ['index' , 'edit' , 'update'] ]);
    		});
    
    $router->group( ['middleware'=> [ 'admin.permission:check,setting'] ] ,
    		function( $router ){
				$router->resource('setting' , 'SettingController' );
    		});
    $router->get( 'fixfinance' , 'FinanceController@fixlog' );    
});
