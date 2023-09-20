<?php

/**
 * Tests: src/Checker/Checks
 */
namespace Sunhill\Basic\Tests\Unit\Checker;

use Sunhill\Basic\Checker\Checks;
use Sunhill\Basic\Checker\CheckException;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\Checker\Checker;

class AnotherDummyChecker extends Checker
{
    
    public function checkSomething(bool $repair)
    {
        $this->pass();
    }
 
}

class ChecksTest extends SunhillOrchestraTestCase
{

    /**
     * Tests: installChecker(), purge()
     */
    public function testInstallChecker()
    {
        $test = new Checks();
        $this->assertTrue(empty($this->getProtectedProperty($test,'checker_classes')),'checker_classes not empty as expected.');
        $test->installChecker("Test");
        $this->assertEquals("Test",$this->getProtectedProperty($test,'checker_classes')[0],'Not expected value after installChecker');
        $test->purge();
        $this->assertTrue(empty($this->getProtectedProperty($test,'checker_classes')),'checker_classes after purge not empty as expected.');
    }
        
    /**
     * @dataProvider ReturnProvider
     * @param unknown $varname
     * @param unknown $methodname
     * Tests: getTestsPerformed(), getTestsPassed(), getTestsFailed(), getTests
     */
    public function testReturnTestsPerformed($varname, $methodname)
    {
        $test = new Checks();
        $this->setProtectedProperty($test, $varname, 5);
        $this->assertEquals(5,$test->$methodname());
        $this->callProtectedMethod($test, 'initializeChecks');
        $this->assertEquals(0,$test->$methodname());
    }
    
    public static function ReturnProvider()
    {
        return [
            ['tests_performed','getTestsPerformed'],
            ['tests_passed','getTestsPassed'],
            ['tests_failed','getTestsFailed'],
            ['tests_repaired','getTestsRepaired'],
            ['tests_unrepairable','getTestsUnrepairable'],
        ];
    }

    /**
     * @dataProvider LastTestProvider
     * @param unknown $method
     * @param unknown $performed
     * @param unknown $passed
     * @param unknown $failed
     * @param unknown $repaired
     * @param unknown $unrepairable
     * Tests: lastCheckPassed, lastCheckFailed, lastCheckRepaired, lastCheckUnrepairable
     */
    public function testLastCheck($method, $performed, $passed, $failed, $repaired, $unrepairable)
    {
        $test = new Checks();
        $this->callProtectedMethod($test, $method,['']);
        $this->assertEquals($performed, $test->getTestsPerformed());
        $this->assertEquals($passed, $test->getTestsPassed());
        $this->assertEquals($failed, $test->getTestsFailed());
        $this->assertEquals($repaired, $test->getTestsRepaired());
        $this->assertEquals($unrepairable, $test->getTestsUnrepairable());
    }
    
    public static function LastTestProvider()
    {
        return [
            ['lastCheckPassed',1,1,0,0,0],
            ['lastCheckFailed',1,0,1,0,0],
            ['lastCheckRepaired',1,0,1,1,0],
            ['lastCheckUnrepairable',1,0,1,0,1],
        ];    
    }
    
    /**
     * @dataProvider processSingleCheckResultProvider
     * @param unknown $method
     * @param unknown $performed
     * @param unknown $passed
     * @param unknown $failed
     * @param unknown $repaired
     * @param unknown $unrepairable
     * Tests: processSingleCheckResult
     */
    public function testProcessSingleCheckResult($method, $performed, $passed, $failed, $repaired, $unrepairable)
    {
        $test = new Checks();
        $checker = new DummyChecker();
        $this->setProtectedProperty($checker, 'last_result', $method);
        
        $this->callProtectedMethod($test, 'processSingleCheckResult', [$checker]);
        
        $this->assertEquals($performed, $test->getTestsPerformed());
        $this->assertEquals($passed, $test->getTestsPassed());
        $this->assertEquals($failed, $test->getTestsFailed());
        $this->assertEquals($repaired, $test->getTestsRepaired());
        $this->assertEquals($unrepairable, $test->getTestsUnrepairable());        
    }
    
    public static function processSingleCheckResultProvider()
    {
        return [
            ['passed',1,1,0,0,0],
            ['failed',1,0,1,0,0],
            ['repaired',1,0,1,1,0],
            ['unrepairable',1,0,1,0,1],
        ];    
    }
    
    /**
     * Tests: callCallback
     */
    public function testCallCallback()
    {
        $test = new Checks();
        $checker = new DummyChecker();
        $this->setProtectedProperty($checker, 'last_result', 'passed');
        $result = '';
        
        $callback = function($checker, $checks) use (&$result) {
            $result = $checker->getLastResult();  
        };
        $this->callProtectedMethod($test, 'callCallback', [$checker, $callback]);
        
        $this->assertEquals('passed', $result);
    }
    
    /**
     * Tests: doPerformSingleCheck
     */
    public function testDoPerformCheck()
    {
        $test = new Checks();
        $checker = new DummyChecker();
        
        $this->callProtectedMethod($test, 'doPerformSingleCheck', [$checker, 'checkFailure', false]);

        $this->assertEquals('FAILED', $checker->getLastMessage());
    }
    
    /**
     * Tests createArrayEntry
     */
    public function testCreateArrayEntry()
    {
        $test = new Checks();
        $checker = new DummyChecker();
        $result = $this->callProtectedMethod($test,'createArrayEntry',[$checker,'test']);
        $this->assertEquals($checker,$result->checker);
        $this->assertEquals('test',$result->method);
    }
    
    /**
     * Tests collectionChecksFromChecker
     */
    public function testCollectChecksFromChecker()
    {
        $test = new Checks();
        $checker = new DummyChecker();
        $result = $this->callProtectedMethod($test,'collectChecksFromChecker',[$checker]);
        usort($result, function ($a, $b) { 
            return ($a->method < $b->method) ? -1 : 1;
            // Note: an equal case isn't possible
        });
        $this->assertEquals(4,count($result));
        $this->assertEquals($checker,$result[0]->checker);
        $this->assertEquals('checkFailure',$result[0]->method);
    }
    
    /**
     * @depends testInstallChecker
     * Tests: collectChecks
     */
    public function testCollectChecks()
    {
        $test = new Checks();
        $test->installChecker(DummyChecker::class);
        $test->installChecker(AnotherDummyChecker::class);
        $result = $this->callProtectedMethod($test, 'collectChecks');
        usort($result, function ($a, $b) {
            return ($a->method < $b->method) ? -1 : 1;
            // Note: an equal case isn't possible
        });
        $this->assertEquals(5, count($result));
        $this->assertEquals('checkFailure',$result[0]->method);
        $this->assertEquals('checkSomething', $result[3]->method);
    }
    
    /**
     * Tests: check()
     */
    public function testRunChecks()
    {
        $test = new Checks();
        $test->installChecker(DummyChecker::class);
        $test->installChecker(AnotherDummyChecker::class);
        
        $test->check();
        
        $this->assertEquals(5, $test->getTotalTests());
        $this->assertEquals(5, $test->getTestsPerformed());
        $this->assertEquals(2, $test->getTestsPassed());
        $this->assertEquals(3, $test->getTestsFailed());
        $this->assertEquals(1, $test->getTestsRepaired());
        $this->assertEquals(1, $test->getTestsUnrepairable());
    }
    
}