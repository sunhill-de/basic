<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Loggable;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;

class LoggableTest extends SunhillOrchestraTestCase
{
    
    public function testLoglevel()
    {
        $test = new Loggable();        
        $this->assertEquals(2,$test->setLoglevel(2)->getLoglevel());
    }

    public function testDisplaylevel()
    {
        $test = new Loggable();
        $this->assertEquals(2,$test->setDisplaylevel(2)->getDisplaylevel());
    }
    
}
