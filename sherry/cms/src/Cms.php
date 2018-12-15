<?php

/**
 * add recomment
 *
 */
namespace Sherry\Cms;

use Closure;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;

/**
 * Class Shop.
 */
class Cms {
	
	/**
	 * 注册后台管理路邮
	 */
	public function registerAdminRoutes() {
		$attributes = [
				'prefix'        => config('shop.admin_prefix'),
				'namespace'     => 'Sherry\Cms\Controllers',
				'middleware'    => ['web', 'admin'],
		];
		
		Route::group($attributes, function ($router) {
			$attributes = ['middleware' => 'admin.permission:allow,administrator'];
		
			/* @var \Illuminate\Routing\Router $router */
			$router->group(
					['middleware'=> [ 'admin.permission:check,posts'] ] ,
					function ($router) {
				$router->resource('cms/category', 'CategoryController');
				$router->resource('cms/posts', 'PostsController');
				$router->resource('cms/single', 'SingleController');
				$router->resource('cms/adv', 'AdvertisementController');
				$router->resource('cms/advtarget', 'AdvtargetController' );
			});
		});
	}
}
