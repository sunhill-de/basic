<?php

namespace Sunhill\Basic\Tests\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Base;

class extension extends Base {

    private $test=0;
    
    public function setTest($value) {
        $this->test = $value;
    }
    
    public function getTest() {
        return $this->test;
    }
}

class BasicTest extends BaseTestCase
{
    use CreatesApplication;
    
    public function testExceptionExists() {
        $this->expectException(SunhillException::class);
        throw new SunhillException();
    }
    
    public function testSetter() {
        $test = new extension();
        $test->test = 2;
        $this->assertEquals(2,$test->test);
    }
    
    public function testException() {
        $this->expectException(SunhillException::class);
        $test = new extension();
        $test->notexisting = 2;
    }
}
