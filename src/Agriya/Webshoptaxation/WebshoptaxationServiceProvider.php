<?php namespace Agriya\Webshoptaxation;

use Illuminate\Support\ServiceProvider;

class WebshoptaxationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('agriya/webshoptaxation');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app['webshoptaxation'] = $this->app->share(function($app)
		{
			return new Webshoptaxation;
		});

		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Webshoptaxation', 'Agriya\Webshoptaxation\Facades\Webshoptaxation');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
