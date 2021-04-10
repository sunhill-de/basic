<?php

namespace Sunhill\Basic;

use Illuminate\Support\ServiceProvider;
use \Sunhill\ORM\Managers\class_manager;
use \Sunhill\ORM\Managers\object_manager;
use \Sunhill\ORM\Managers\tag_manager;

class SunhillServiceProvider extends ServiceProvider
{
    public function register()
    {        
    }
    
    public function boot()
    {
    }
}
