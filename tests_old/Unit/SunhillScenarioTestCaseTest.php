<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\base;
use Tests\CreatesApplication;

class FakeScenario {

    public $flag = 'Empty';
    public $test;
    
    
    public function SetupBeforeTests() {
        $this->flag .= 'SetupBeforeTests';
        return $this;
    }
    
    public function Setup() {
        $this->flag .= 'Setup';
        return $this;
    }
 
    public function SetTest($test) {
        $this->test = $test;
        return $this;
    }
}

class SunhillScenarioTestCaseTest extends SunhillScenarioTestCase
{
   
    protected function GetScenarioClass() {
        return FakeScenario::class;
    }
    
    public function testSetups() {
        $this->assertEquals('EmptySetupBeforeTestsSetup',$this->GetScenario()->flag);
    }
    
    public function testSetups2() {
        $this->assertEquals('EmptySetupBeforeTestsSetupSetup',$this->GetScenario()->flag);
    }

    public function testGetTest() {
        $scenario = $this->GetScenario();
        $this->assertEquals($this,$scenario->test);
    }
    
}
