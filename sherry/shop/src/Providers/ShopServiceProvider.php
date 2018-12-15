<?php

namespace Sherry\Shop\Providers;


use Sherry\Shop\Facades\Shop;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;


class ShopServiceProvider extends ServiceProvider
{
	
	/**
	 * @var array
	 */
	protected $commands = [
	];
	
	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
	];
	
	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
	];
	
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    	$this->loadViewsFrom(__DIR__.'/../../views', 'shop');
    	$this->loadTranslationsFrom(__DIR__.'/../../lang/', 'shop');
    	
    	/**
    	$this->publishes([__DIR__.'/../../config/admin.php' => config_path('admin.php')], 'laravel-admin');
    	$this->publishes([__DIR__.'/../../assets' => public_path('packages/admin')], 'laravel-admin');
    	**/
    	Shop::registerAdminRoutes();
    	
    	if (file_exists($routes = admin_path('routes.php'))) {
    		require $routes;
    	}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    	$this->app->booting(function () {
    		$loader = AliasLoader::getInstance();
    	
    		$loader->alias('Shop', \Sherry\Shop\Facades\Shop::class);
    		/**
    		if (is_null(config('auth.guards.admin'))) {
    			$this->setupAuth();
    		}
    		**/
    	});
    }
    
    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function setupAuth()
    {
    	config([
    			'auth.guards.admin.driver'    => 'session',
    			'auth.guards.admin.provider'  => 'admin',
    			'auth.providers.admin.driver' => 'eloquent',
    			'auth.providers.admin.model'  => 'Encore\Admin\Auth\Database\Administrator',
    	]);
    }
}
