<?php

namespace Laralib\L5scaffold;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{

	    $this->publishStubFiles();

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->registerScaffoldGenerator();

	}


	/**
	 * Register the make:scaffold generator.
	 */
	private function registerScaffoldGenerator()
	{
		$this->app->singleton('command.larascaf.scaffold', function ($app) {
			return $app['Laralib\L5scaffold\Commands\ScaffoldMakeCommand'];
		});

		$this->commands('command.larascaf.scaffold');
	}

	/**
     * Publish the stubs to the config folder
     */
	private function publishStubFiles()
    {

        $this->publishes([__DIR__ . '/stubs/' => config_path('l5scaffold/stubs/')], 'stubs');

    }


}
