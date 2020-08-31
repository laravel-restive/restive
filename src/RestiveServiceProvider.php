<?php

namespace Restive;

use Illuminate\Support\ServiceProvider;

class RestiveServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('restive', Restive::class);
    }

    public function boot()
    {
        if (app()->runningUnitTests()) {
            $this->loadRoutesFrom(__DIR__.'/../tests/Fixtures/routes/api.php');
        }
        $this->loadConfigs();
        $this->publishFiles();
    }

    protected function loadConfigs()
    {
        $this->mergeConfigFrom(__DIR__.'/config/restive.php', 'restive');
    }

    protected function publishFiles()
    {
        $config_files = [__DIR__.'/config' => config_path()];
        $this->publishes($config_files, 'config');
    }
}
