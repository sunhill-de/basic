<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\base;
use Tests\CreatesApplication;

class SunhillTestCaseTestClass {

    protected $protectedValue = 'A';
    
    protected function SetValue($value) {
        $this->protectedValue = $value;
    }
    
    public function GetValue() {
        return $this->protectedValue;
    }
}

class SunhillTestCaseTest extends SunhillTestCase
{
   
    use CreatesApplication;
    
    public function testNullTest() {
        $test = new SunhillTestCaseTestClass();
        $this->assertEquals('A',$test->GetValue());
    }
    
    public function testSetProtectedProperty() {
        $test = new SunhillTestCaseTestClass();
        $this->setProtectedProperty($test,'protectedValue','B');
        $this->assertEquals('B',$test->GetValue());
    }
    
    public function testGetProtectedProperty() {
        $test = new SunhillTestCaseTestClass();
        $this->assertEquals('A',$this->getProtectedProperty($test,'protectedValue'));        
    }
    
    public function testCallProtectedMethod() {
        $test = new SunhillTestCaseTestClass();
        $this->invokeMethod($test,'SetValue',['B']);
        $this->assertEquals('B',$test->GetValue());
    }
    
    public function testCheckArrayPass() {
        $this->assertTrue($this->checkArrays(['A','B','C'],['A','B','C']));
    }
    
    public function testCheckArrayPass2() {
        $this->assertTrue($this->checkArrays(['A','B','C'],['A','B','C','D']));
    }
    
    public function testCheckArrayFail() {
        $this->assertTrue($this->checkArrays(['A','B'],['D','E','C']));
    }
    
    public function testCheckArrayFail2() {
        $this->assertTrue($this->checkArrays(['A','B','C'],['A','B','D']));
    }
    
    public function testAssertArrayContainsPass() {
        $this->assertArrayContains(['A','B','C'],['A','B','C']);
    }
    
    public function testAssertArrayContainsyPass2() {
        $this->assertArrayContains(['A','B','C'],['A','B','C','D']);
    }

    public function testGetFieldSimple() {
        $test = new \StdClass();
        $test->test = 'A';
        $this->assertEquals('A',$this->get_field($test,'test'));
    }
    
    public function testGetFieldArray1() {
        $test = new \StdClass();
        $test->test = ['A','B','C'];
        $this->assertEquals('A',$this->get_field($test,'test[0]'));
    }
    
    public function testGetFieldArray2() {
        $test = new \StdClass();
        $test->test = ['A'=>1,'B'=>2,'C'=>3];
        $this->assertEquals(1,$this->get_field($test,'test[A]'));
    }
        
    public function testGetFieldObject() {
        $test = new \StdClass();
        $test->test = new \StdClass();
        $test->test->subtest = 'A';
        $this->assertEquals('A',$this->get_field($test,'test->subtest'));
    }
    
    public function testGetArrayFieldObject() {
        $test = new \StdClass();
        $test->test = [new \StdClass()];
        $test->test[0]->subtest = 'A';
        $this->assertEquals('A',$this->get_field($test,'test[0]->subtest'));
    }
   
    public function testGetTempDir() {
        exec("rm -rf ".storage_path('/temp'));
        $this->assertFalse(file_exists(storage_path('/temmp')));
        $this->assertTrue(file_exists($this->GetTempDir()));
    }
}
