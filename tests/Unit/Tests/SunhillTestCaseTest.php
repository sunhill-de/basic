<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\base;
use Tests\CreatesApplication;
use Illuminate\Support\Facades\DB;

class SunhillOrchestraTestCaseTestClass {

    protected $protectedValue = 'A';
    
    protected function SetValue($value) {
        $this->protectedValue = $value;
    }
    
    public function GetValue() {
        return $this->protectedValue;
    }
}

class SunhillTestCaseTest extends SunhillOrchestraTestCase
{
   
    public function testNullTest() {
        $test = new SunhillOrchestraTestCaseTestClass();
        $this->assertEquals('A',$test->GetValue());
    }
    
    public function testSetProtectedProperty() {
        $test = new SunhillOrchestraTestCaseTestClass();
        $this->setProtectedProperty($test,'protectedValue','B');
        $this->assertEquals('B',$test->GetValue());
    }
    
    public function testGetProtectedProperty() {
        $test = new SunhillOrchestraTestCaseTestClass();
        $this->assertEquals('A',$this->getProtectedProperty($test,'protectedValue'));        
    }
    
    public function testCallProtectedMethod() {
        $test = new SunhillOrchestraTestCaseTestClass();
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

    public function testCheckStdClass_pass1() 
    {
        $test = new \StdClass();
        $test->a = 10;
        $test->b = 20;
        $this->assertStdClassHasValues(['a'=>10,'b'=>20], $test);
    }
    
    public function testCheckStdClass_pass2()
    {
        $test = new \StdClass();
        $test->a = 10;
        $test->b = 20;
        $this->assertStdClassHasValues(['a'=>10], $test);
    }
    
    public function testCheckStdClass_fail1()
    {
        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        $test = new \StdClass();
        $test->a = 10;
        $this->assertStdClassHasValues(['a'=>10,'b'=>20], $test);
    }
    
    public function testCheckStdClass_fail2()
    {
        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        $test = new \StdClass();
        $test->a = 11;
        $test->b = 20;
        $this->assertStdClassHasValues(['a'=>10,'b'=>20], $test);
    }
    
    public function testCheckStdClass_fail3()
    {
        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        $test = new \StdClass();
        $test->a = 11;
        $test->b = 20;
        $this->assertStdClassHasValues(['a'=>10,'c'=>20], $test);
    }
    
    public function testGetFieldSimple() {
        $test = new \StdClass();
        $test->test = 'A';
        $this->assertEquals('A',$this->getField($test,'test'));
    }
    
    public function testGetFieldArray1() {
        $test = new \StdClass();
        $test->test = ['A','B','C'];
        $this->assertEquals('A',$this->getField($test,'test[0]'));
    }
    
    public function testGetFieldArray2() {
        $test = new \StdClass();
        $test->test = ['A'=>1,'B'=>2,'C'=>3];
        $this->assertEquals(1,$this->getField($test,'test[A]'));
    }
        
    public function testGetFieldObject() {
        $test = new \StdClass();
        $test->test = new \StdClass();
        $test->test->subtest = 'A';
        $this->assertEquals('A',$this->getField($test,'test->subtest'));
    }
    
    public function testGetArrayFieldObject() {
        $test = new \StdClass();
        $test->test = [new \StdClass()];
        $test->test[0]->subtest = 'A';
        $this->assertEquals('A',$this->getField($test,'test[0]->subtest'));
    }
   
    public function testGetTempDir() {
        exec("rm -rf ".storage_path('/temp'));
        $this->assertFalse(file_exists(storage_path('/temp')));
        $this->assertTrue(file_exists($this->GetTempDir()));
    }
    
    public function testDatabaseHasTableFail() {
        DB::statement('drop table if exists testtable');
        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        $this->assertDatabaseHasTable('testtable');
    }
    
    public function testDatabaseHasTablePass() {
        DB::statement('create table testtable (id int)');
        $this->assertDatabaseHasTable('testtable');
        DB::statement('drop table if exists testtable');
    }
    
    
    public function testDatabaseHasNotTableFail() {
        DB::statement('drop table if exists testtable');
        $this->assertDatabaseHasNotTable('testtable');
    }
    
    public function testDatabaseHasNotTablePass() {
        DB::statement('drop table if exists testtable');
        DB::statement('create table testtable (id int)');
        $this->expectException('PHPUnit\Framework\ExpectationFailedException');
        $this->assertDatabaseHasNotTable('testtable');
    }
}
