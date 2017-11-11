<?php

namespace Aries\Gateway;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$migrations = __DIR__ . '/../migrations/';
		$views = __DIR__ . '/../views/';


		// php artisan vendor:publish --provider=Larabookir\Gateway\GatewayServiceProvider --tag=migrations
		$this->publishes([
			$migrations => base_path('database/migrations')
		], 'migrations');

		
		
		if (
			File::glob(base_path('/database/migrations/*create_gateway_status_log_table\.php'))
			&& !File::exists(base_path('/database/migrations/2017_04_05_103357_alter_id_in_transactions_table.php'))
		) {
			@File::copy($migrations.'/2017_04_05_103357_alter_id_in_transactions_table.php',base_path('database/migrations/2017_04_05_103357_alter_id_in_transactions_table.php'));
		}


		$this->loadViewsFrom($views, 'gateway');

		// php artisan vendor:publish --provider=Larabookir\Gateway\GatewayServiceProvider --tag=views
		$this->publishes([
			$views => base_path('resources/views/vendor/gateway'),
		], 'views');

		//$this->mergeConfigFrom( $config,'gateway')
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('gateway', function () {
			return new GatewayResolver();
		});

	}
}