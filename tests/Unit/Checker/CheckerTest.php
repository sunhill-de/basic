<?php

namespace Sunhill\Basic\Tests\Unit\Checker;

use Sunhill\Basic\Checker\Checker;
use Sunhill\Basic\Checker\CheckException;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;

class CheckerTest extends SunhillOrchestraTestCase
{
    
    /**
     * Tests: src/Checker/Checker::pass()
     */
    public function testPass()
    {
        $test = new DummyChecker();
        $test->checkPass(false);
        $this->assertEquals('passed',$test->getLastResult());
    }
    
    /**
     * Tests: src/Checker/Checker::fail(), getLastResult(), getLastMessage()
     */
    public function testFailure()
    {
        $test = new DummyChecker();
        try {
            $test->checkFailure(false);
        } catch (CheckException $e) {
            $this->assertEquals('failed',$test->getLastResult());
            $this->assertEquals('FAILED',$test->getLastMessage());
            return;
        }
        $this->assertFalse(true);
    }
    
    /**
     * Tests: src/Checker/Checker::repair(), getLastResult(), getLastMessage()
     */
    public function testRepair()
    {
        $test = new DummyChecker();        
        try {
            $test->checkRepair(true);
        } catch (CheckException $e) {
            $this->assertEquals('repaired',$test->getLastResult());
            $this->assertEquals('REPAIRED',$test->getLastMessage());
            return;
        }
        $this->assertFalse(true);
    }
    
    /**
     * Tests: src/Checker/Checker::unrepairable(), getLastResult(), getLastMessage()
     */
    public function testUnrepairable()
    {
        $test = new DummyChecker();
        try {
            $test->checkUnrepairable(true);
        } catch (CheckException $e) {
            $this->assertEquals('unrepairable',$test->getLastResult());
            $this->assertEquals('UNREPAIRABLE',$test->getLastMessage());
            return;
        }
        $this->assertFalse(true);
    }
    
}