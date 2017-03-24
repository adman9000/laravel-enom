<?php namespace onethirtyone\enomapi;

/**
 * @author  131 Studios <contact@131studios.com>
 */
use Illuminate\Support\ServiceProvider;

class EnomServiceProvider extends ServiceProvider {

	public function boot() 
	{
		$this->publishes([
			__DIR__.'/config/enomapi.php' => config_path('enomapi.php')
		]);
	} // boot

	public function register() 
	{
		$this->app->bind('enom-api', function($app) {
			return new EnomAPI($app);
		});

		$this->mergeConfigFrom(
			__DIR__.'/config/enomapi.php', 'enomapi');

	} // register
}