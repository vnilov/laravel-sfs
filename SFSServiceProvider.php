<?php

namespace SimpleFileStorage;

use \Illuminate\Support\ServiceProvider;

class SFSServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'config/sfs.php' => config_path('sfs.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('SimpleFileStorage\SFSFacade', 'SimpleFileStorage\FacadeL52');
        $this->app->bind('SimpleFileStorage\SFS', function($app) {
            return new SFSL52($app['SimpleFileStorage\SFSFacade']);
        });
    }
}