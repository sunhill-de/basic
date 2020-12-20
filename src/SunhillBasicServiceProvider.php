<?php

namespace Sunhill\basic;

use Illuminate\Support\ServiceProvider;
use Sunhill\Basic\Checker\checks;

class SunhillBasicServiceProvider extends ServiceProvider
{
    public function register()
    {        
        $this->app->singleton(checks::class, function () { return new checks(); } );
        $this->app->alias(checks::class,'checks');
    }
    
    public function boot()
    {
    }
}
