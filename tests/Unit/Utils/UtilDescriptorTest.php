<?php
namespace Sunhill\Basic\Tests\Unit;

/**
 *
 * @file UtilDescriptorTest.php
 * lang: en
 * dependencies: FilemanagerTestCase
 */
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Basic\Utils\DescriptorException;
use Sunhill\Basic\Tests\Unit\CreatesApplication;

class test_descriptor extends Descriptor {
    
    
    protected $autoadd = false;
    
    public $flag = '';
    
    protected function setupFields() {
        $this->test = 'ABC';
        $this->test2 = 'DEF';
    }
    
    protected function test_changing(Descriptor $diff) {
        if ($diff->from == 'ABC') {
            return true;
        } else {
            return false;
        }
    }
    
    protected function test_changed(Descriptor $diff) {
        $this->flag = $diff->from."=>".$diff->to;    
    }
}

class UtilDescriptorTest extends SunhillOrchestraTestCase
{

    public function testSetGet()
    {
        $test = new Descriptor();
        $test->test = 'ABC';
        $this->assertEquals('ABC', $test->test);
    }

    public function testNotSet()
    {
        $test = new Descriptor();
        $this->assertTrue($test->notset->empty());
    }

    public function testEmpty()
    {
        $test = new Descriptor();
        $this->assertTrue($test->empty());
        $this->assertFalse($test->hasError());
    }

    public function testError()
    {
        $test = new Descriptor();
        $test->set_error('There was an error');
        $this->assertEquals('There was an error', $test->hasError());
    }

    /**
     *
     * @group double
     */
    public function testDoubleDescriptor()
    {
        $test = new Descriptor();
        $test->test1 = 'ABC';
        $test->test2->test = 'ABC';
        $this->assertEquals($test->test1, $test->test2->test);
    }

    public function testForeach()
    {
        $test = new Descriptor();
        $test->test1 = 'ABC';
        $test->test2 = 'BCE';
        $test->anothertest = 123;
        $result = '';
        foreach ($test as $key => $value) {
            $result .= $key . '=>' . $value;
        }
        $this->assertEquals('test1=>ABCtest2=>BCEanothertest=>123', $result);
    }

    public function testGet()
    {
        $test = new Descriptor();
        $test->test1 = 'ABC';
        $this->assertEquals('ABC', $test->get_test1());
    }

    public function testSet()
    {
        $test = new Descriptor();
        $test->set_test1('ABC');
        $this->assertEquals('ABC', $test->test1);
    }

    public function testCascadingSet()
    {
        $test = new Descriptor();
        $test->set_test1('ABC')->set_test2('DEF');
        $this->assertEquals('DEF', $test->test2);
    }

    public function testRaiseException()
    {
        $this->expectException(DescriptorException::class);
        $test = new Descriptor();
        $test->not_existing_function();
    }
    
    public function testNoAutoadd() {
        $this->expectException(DescriptorException::class);
        $test = new test_descriptor();
        $test->test3 = 'ABC';
    }
    
    public function testPassChange() {
        $test = new test_descriptor();
        $test->test = 'CBA';
        $this->assertEquals('CBA',$test->test);
        return $test;
    }
    
    /**
     * @depends testPassChange
     */
    public function testFailChange(test_descriptor $test) {
        $this->expectException(DescriptorException::class);
        $test->test = 'ZZZ';
    }

    /**
     * @depends testPassChange
     */
    public function testPostTrigger(test_descriptor $test) {
        $this->assertEquals('ABC=>CBA',$test->flag);
    }
    
    public function testHasKey() {
        $test = new Descriptor();
        $test->abc = 'abc';
        $this->assertTrue($test->isDefined('abc'));
        $this->assertFalse($test->isDefined('notdefined'));
    }
    
    public function testAssertHasKey() {
        $test = new Descriptor();
        $test->abc = 'abc';
        $this->assertTrue($test->assertHasKey('abc'));
        $this->assertFalse($test->assertHasKey('notdefined'));    
    }
        
    public function testAssertKeyIs() {
        $test = new Descriptor();
        $test->abc = 'abc';
        $this->assertTrue($test->assertKeyIs('abc','abc'));
        $this->assertFalse($test->assertKeyIs('notdefined','abc'));    
        $this->assertFalse($test->assertKeyIs('abc','def'));    
    }
    
    public function testAssertKeyHas() {
        $test = new Descriptor();
        $test->abc = ['abc','def','ghi'];
        $this->assertTrue($test->assertKeyHas('abc','abc'));
        $this->assertFalse($test->assertKeyHas('notdefined','abc'));    
        $this->assertFalse($test->assertKeyHas('abc','xyz'));    
    }
    
}
