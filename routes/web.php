<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/test' , 'HomeController@test' )->name('test');
Route::get('/home' , 'HomeController@index' )->name('home');

Route::get('/', 'HomeController@index')->name('index');
Route::get('checkin' , 'HomeController@checkin')->name('checkin')->middleware('auth:web') ;
Route::get('track' , 'HomeController@track')->name('track') ;
Route::get('posts/{id}' , 'HomeController@posts')->name('posts');
Route::get('posts/show/{id}' , 'HomeController@postsdetail')->name('pc.posts.show');
Route::get('page/{id}' , 'HomeController@page')->name('singlepage') ;
Route::get('getship' , 'HomeController@getship')->name('getship');
Route::get('getflight' , 'HomeController@getflight')->name('getflight');
Route::get('checkprice' , 'HomeController@checkprice')->name('checkprice');
Route::get('rec' , function(){
	return view('recom');
})->name('recom');
Route::get('portprice' , 'HomeController@portprice')->name('portprice');
Route::get('flight' , "HomeController@flightlist" )->name('flight');

Route::get('findpwd' , function(){
	return view('home.findpwd');
})->name('findpwd');
Route::post('findpwd' , 'HomeController@findpwd')->name('findpwd');
Route::post('sendsms' , 'HomeController@sendsms' )->name('sendsms');
Auth::routes();

Route::group([  'middleware' => ['web' , 'auth'] ] , function(){
	Route::post('checkin' , 'HomeController@check')->name('checkin');
	Route::get('checkinchange/{id}' , 'HomeController@checkchange' )->name('checkinchange');
	Route::post('checkinchange/{id}' , 'HomeController@updatecheckchange' )->name('checkinchange');
	
	Route::get('member' , 'MemberController@order')->name('member');
	Route::get('member/recom' , 'MemberController@recom')->name('member.recom');
	Route::get('member/order' , 'MemberController@order')->name('member.order');
	Route::get('member/order/{id}' , 'MemberController@orderdetail' )->name('member.order.show');
	Route::get('member/order/{id}/export' , 'MemberController@orderexport' )->name('member.order.export');
	Route::get('member/exportgoods' , 'MemberController@exportgoods')->name('order.exportgoods');
	Route::get('member/refund' , 'MemberController@refund')->name('member.refund');
	Route::get('member/reward' , 'MemberController@reward')->name('member.reward');
	Route::get('member/modpwd' , function(){
		$user = auth()->guard('web')->user() ;
		return view('member.modpwd', ['leftMenu' => 'modpwd' , 'user' => $user ] );
	})->name('member.modpwd' );
	Route::post('member/modpwd' , 'MemberController@modpwd' )->name('member.modpwd');
	Route::get('member/userinfo' , 'MemberController@userinfo')->name('member.userinfo');
	Route::post('member/userinfo' , 'MemberController@userinfostore')->name('member.userinfo');
	
	//银行卡管理
	Route::get('member/bank' , 'MemberController@bank')->name('member.bank');
	Route::get('member/bank/create' , 'MemberController@bankcreate')->name('member.bank.create');
	Route::post('member/bank/create' , 'MemberController@bankstore')->name('member.bank.create');
	Route::get('member/bank/{id}' , 'MemberController@bankedit')->name('member.bank.edit');
	Route::post('member/bank/{id}' , 'MemberController@bankupdate')->name('member.bank.edit');
	Route::get('member/bank/{id}/delete' , 'MemberController@bankdrop')->name('member.bank.delete');
	
	//提现管理
	Route::get('member/withdraw' , 'WithdrawController@index')->name('member.withdraw');
	Route::get('member/withdraw/create' , 'WithdrawController@create')->name('member.withdraw.create');
	Route::post('member/withdraw/create' , 'WithdrawController@store')->name('member.withdraw.create');
	Route::get('member/withdraw/{id}/cancel' , 'WithdrawController@cancel')->name('member.withdraw.cancel');

});


//wap
Route::group([ 'namespace' => 'Wap' , 'middleware' => ['web', 'wechat.oauth'] , 'prefix' => 'wap' ], function ( $route ) {
	Route::get('/user', function () {
		$user = session('wechat.oauth_user'); // 拿到授权用户资料

		dd($user);
	});
	
	Route::get('/' , 'HomeController@index')->name('wap.index');
	Route::get('posts/{id}' , 'HomeController@posts')->name('wap.posts');
	Route::get('show/{id}' , 'HomeController@show')->name('wap.show');
	Route::get('page/{id}' , 'HomeController@page')->name('wap.page');
	Route::get('login' , 'HomeController@login')->name('wap.login');
	Route::post('login' , 'HomeController@dologin')->name('wap.login');
	Route::get('track' , 'HomeController@track')->name('wap.track');
	Route::get('recom' , 'HomeController@recom')->name('wap.recom');
	Route::get('logout' , 'HomeController@logout')->name('wap.logout');
	Route::get('register' , 'HomeController@register')->name('wap.register');
	Route::post('register' , 'HomeController@doregister')->name('wap.register');
	Route::get('findpwd' , 'HomeController@findpwd')->name('wap.findpwd');
	Route::post('findpwd' , 'HomeController@dofindpwd')->name('wap.findpwd');
	Route::get('flight/hot' , "HomeController@flighthot" )->name('wap.flight.hot');
	Route::get('flight/{id}' , 'HomeController@flightlist' )->name('wap.flight');
	Route::get('portprice' , 'HomeController@portprice')->name('wap.portprice');
	Route::get('searchport' , 'HomeController@searchport')->name('wap.searchport');
	
});

Route::group([ 'namespace' => 'Wap' , 'middleware' => ['web', 'wechat.oauth' , 'auth:wap'] , 'prefix' => 'wap' ], function ( $route ) {
	
	Route::get('member' , 'MemberController@index')->name('wap.member');
	Route::get('member/setting' , 'MemberController@setting')->name('wap.setting');
	Route::get('member/recom' , 'MemberController@recom')->name('wap.member.recom');
	Route::get('member/modpwd' , 'MemberController@modpwd')->name('wap.modpwd');
	Route::post('member/modpwd' , 'MemberController@updatepwd')->name('wap.modpwd');
	
	Route::get('member/modinfo' , 'MemberController@modinfo')->name('wap.modinfo');
	Route::post('member/modinfo' , 'MemberController@updateinfo')->name('wap.modinfo');
	
	Route::get('member/withdraw' , 'MemberController@withdraw')->name('wap.member.withdraw');
	Route::get('member/withdrawcancel/{id}' , 'MemberController@withdrawcancel')->name('wap.withdraw.cancel');
	Route::get('member/withdrawcreate' , 'MemberController@withdrawcreate')->name('wap.withdraw.create');
	Route::post('member/withdrawcreate' , 'MemberController@withdrawstore')->name('wap.withdraw.create');
	Route::get('member/bank' , 'MemberController@bank')->name('wap.member.bank');
	Route::get('member/order' , 'MemberController@order')->name('wap.member.order');
	Route::get('member/order/{id}' , 'MemberController@orderdetail')->name('wap.member.orderdetail');
	Route::get('member/refund' , 'MemberController@refund')->name('wap.member.refund');
	Route::get('member/reward' , 'MemberController@reward')->name('wap.member.reward');
	Route::get('member/qrcode' , 'MemberController@qrcode' )->name('wap.member.qrcode');
	
	Route::get('member/bank' , 'MemberController@bank')->name('wap.member.bank');
	Route::get('member/bank/create' , 'MemberController@bankcreate')->name('wap.bank.create');
	Route::post('member/bank/create' , 'MemberController@bankstore')->name('wap.bank.create');
	Route::get('member/bank/{id}' , 'MemberController@bankedit')->name('wap.bank.edit');
	Route::post('member/bank/{id}' , 'MemberController@bankupdate')->name('wap.bank.edit');
	Route::get('member/bank/{id}/delete' , 'MemberController@bankdrop')->name('wap.bank.delete');
	
	Route::get('checkin' , 'HomeController@checkin')->name('wap.checkin');
	Route::post('checkin' , 'HomeController@checkinstore')->name('wap.checkin');
	
	Route::get('entrust' , 'HomeController@getentrust' )->name('wap.checkin.entrust');
	Route::post('entrust' , 'HomeController@setentrust' )->name('wap.checkin.entrust');
	
	Route::get('sender' , 'HomeController@getsender' )->name('wap.checkin.sender');
	Route::post('sender' , 'HomeController@setsender' )->name('wap.checkin.sender');
	
	Route::get('recevier' , 'HomeController@getrecevier' )->name('wap.checkin.recevier');
	Route::post('recevier' , 'HomeController@setrecevier' )->name('wap.checkin.recevier');
	
	Route::get('ensure' , 'HomeController@ensure' )->name('wap.checkin.ensure');
	Route::post('checksub' , 'HomeController@submit' )->name('wap.checkin.submit');
});
