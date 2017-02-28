<?php namespace studios131\enomapi;

/**
 * @author  131 Studios <contact@131studios.com>
 */

use Illuminate\Support\ServiceProvider;

class EnomServiceProvider extends ServiceProvider {

	protected	$defer = false;

	public function boot() 
	{
		$this->publishes([
			__DIR__.'/config/enomapi.php' => config_path('enomapi.php')
		]);

		$this->loadMigrationsFrom(__DIR__.'/database/migrations/2016_09_09_205117_create_domains_table.php');
	} // boot

	public function register() 
	{
		$this->app->bind('enomapi', function($app) {
			return new EnomApi($app);
		});

		$this->mergeConfigFrom(
			__DIR__.'/config/enomapi.php', 'enomapi');

	} // register
}