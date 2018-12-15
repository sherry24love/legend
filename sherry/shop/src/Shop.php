<?php

namespace Sherry\Shop;

use Closure;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;

/**
 * Class Shop.
 */
class Shop {
	
	/**
	 * 注册后台管理路邮
	 */
	public function registerAdminRoutes() {
		$attributes = [
				'prefix'        => config('shop.admin_prefix'),
				'namespace'     => 'Sherry\Shop\Controllers',
				'middleware'    => ['web', 'admin'],
		];
		
		Route::group($attributes, function ($router) {
			$attributes = ['middleware' => 'admin.permission:allow,administrator'];
		
			/* @var \Illuminate\Routing\Router $router */
			$router->group($attributes, function ($router) {
				$router->resource('shop/category', 'CategoryController' , ['except' => ['create']]);
				$router->resource('shop/products', 'ProductsController');
				$router->resource('shop/orders', 'OrdersController');
				$router->resource('shop/type', 'GoodsTypeController' );
				$router->resource('shop/brand', 'BrandController' );
				
				//商品属性的事情修改一下操作
				//$router->get('shop/attributes/{id}/create', 'AttributesController@create');
				//$router->post('shop/attributes/{id}', 'AttributesController@store');
				$router->resource('shop/attributes', 'AttributesController');
			});
		});
	}
}