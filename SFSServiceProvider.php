<?php

namespace SimpleFileStorage;


use Illuminate\Support\ServiceProvider;

class SFSServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        
    }

    public function register()
    {
        $this->app->bind('SimpleFileStorage\Facade', 'SimpleFileStorage\FacadeL52');
        $this->app->bind('SimpleFileStorage\SFS', function($app) {
            return new SFSL52($app['SimpleFileStorage\Facade']);
        });
    }
}