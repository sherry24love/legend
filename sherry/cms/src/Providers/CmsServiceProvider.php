<?php

namespace Sherry\Cms\Providers;


use Sherry\Cms\Facades\Cms;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;


class CmsServiceProvider extends ServiceProvider
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
    	$this->loadViewsFrom(__DIR__.'/../../views', 'cms');
    	$this->loadTranslationsFrom(__DIR__.'/../../lang/', 'cms');
    	
    	/**
    	$this->publishes([__DIR__.'/../../config/admin.php' => config_path('admin.php')], 'laravel-admin');
    	$this->publishes([__DIR__.'/../../assets' => public_path('packages/admin')], 'laravel-admin');
    	**/
    	Cms::registerAdminRoutes();
    	
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
    	
    		$loader->alias('Cms', \Sherry\Cms\Facades\Cms::class);
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
