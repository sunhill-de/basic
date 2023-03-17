<?php

namespace Sunhill\Basic\Tests\Unit\Checker;

use Sunhill\Basic\Checker\Checks;
use Sunhill\Basic\Checker\CheckException;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;

class ChecksTest extends SunhillOrchestraTestCase
{

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
     */
    public function testReturnTestsPerformed($varname, $methodname)
    {
        $test = new Checks();
        $this->setProtectedProperty($test, $varname, 5);
        $this->assertEquals(5,$test->$methodname());
        $this->callProtectedMethod($test, 'initializeChecks');
        $this->assertEquals(0,$test->$methodname());
    }
    
    public function ReturnProvider()
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
    
    public function LastTestProvider()
    {
        return [
            ['lastCheckPassed',1,1,0,0,0],
            ['lastCheckFailed',1,0,1,0,0],
            ['lastCheckRepaired',1,0,1,1,0],
            ['lastCheckUnrepairable',1,0,1,0,1],
        ];    
    }
        
}