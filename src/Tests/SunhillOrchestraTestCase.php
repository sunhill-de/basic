<?php

namespace Sunhill\Basic\Tests;

use Sunhill\Basic\SunhillBasicServiceProvider;
use Orchestra\Testbench\TestCase;

class SunhillOrchestraTestCase extends TestCase
{
    
    use SunhillTrait;
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    protected function getPackageProviders($app)
    {
        return [
            SunhillBasicServiceProvider::class,
        ];
    }
    
}