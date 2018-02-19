<?php 
/**
 * @author  Adman9000 <myaddressistaken@googlemail.com>
 */
 namespace adman9000\enom;


use Illuminate\Support\ServiceProvider;

class EnomServiceProvider extends ServiceProvider {

	public function boot() 
	{
		$this->publishes([
			__DIR__.'/config/enom.php' => config_path('enom.php')
		]);
	} // boot

	public function register() 
	{
		$this->app->bind('enom-api', function($app) {
			return new EnomAPI($app);
		});

		$this->mergeConfigFrom(
			__DIR__.'/config/enom.php', 'enomapi');

	} // register
}
