<?php

namespace Sunhill\Basic\Tests\Unit\Checker;

use Sunhill\Basic\Checker\CheckAtom;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;

class TestCheckAtom extends CheckAtom {

    public $should_fail = false;
    
    protected function doRun(): bool
    {
        if ($this->should_fail) {
            $this->testFailed(1, 1, "The test failed.");
            return false;
        } else {
            $this->testPassed();
            return true;
        }
    }
}

class CheckAtomTest extends SunhillOrchestraTestCase
{

    public function testCorrect()
    {
        $test = new TestCheckAtom();
        $this->assertFalse($this->getProtectedProperty($test,'correct'));
        $test->setCorrect();
        $this->assertTrue($this->getProtectedProperty($test,'correct'));
    }
    
    public function testSuccessfulRun()
    {
        $test = new TestCheckAtom();
        $this->assertTrue($test->run());
        $this->assertEquals(1,$test->getSubtestsRun());
        $this->assertEquals(0,$test->getSubtestsFailed());
        $this->assertEquals(1,$test->getSubtestsPassed());
    }
    
    public function testFailureRun()
    {
        $test = new TestCheckAtom();
        $test->should_fail = true;
        $this->assertFalse($test->run());
        $this->assertEquals(1,$test->getSubtestsRun());
        $this->assertEquals(1,$test->getSubtestsFailed());
        $this->assertEquals(0,$test->getSubtestsPassed());
        $this->assertEquals("The test failed.",$test->getFailureMessage());
    }
}
