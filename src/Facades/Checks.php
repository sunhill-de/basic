<?php

namespace Sunhill\Basic\Facades;

use Illuminate\Support\Facades\Facade;

class Checks extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'checks';
    }
}
