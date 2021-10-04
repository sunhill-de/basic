<?php

namespace Sunhill\Basic;

use Illuminate\Support\ServiceProvider;
use Sunhill\Basic\Checker\Checks;
use Sunhill\Basic\Console\Check;

class SunhillBasicServiceProvider extends ServiceProvider
{
    public function register()
    {        
        $this->app->singleton(Checks::class, function () { return new Checks(); } );
        $this->app->alias(Checks::class,'checks');
    }
    
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Check::class,
            ]);
        }
    }
}
