<?php

namespace SimpleFileStorage;

use \Illuminate\Support\ServiceProvider;

class SFSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sfs.php' => config_path('sfs.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/database/factories/' => database_path('factories')
        ], 'factories');

        $this->publishes([
            __DIR__ . '/tests/' => base_path('tests')
        ], 'tests');
    }

    public function register()
    {
        $this->app->bind('SimpleFileStorage\SFSFacade', 'SimpleFileStorage\SFSFacadeL52');
    }
}