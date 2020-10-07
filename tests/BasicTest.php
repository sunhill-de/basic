<?php

namespace Sunhill\ORM\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\base;

class extension extends base {

    private $test=0;
    
    public function set_test($value) {
        $this->test = $value;
    }
    
    public function get_test() {
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
        $this->notexisting = 2;
    }
}
