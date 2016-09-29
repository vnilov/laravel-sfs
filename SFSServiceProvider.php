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
    }

    public function register()
    {
        $this->app->bind('SimpleFileStorage\SFSFacade', 'SimpleFileStorage\SFSFacadeL52');
    }
}