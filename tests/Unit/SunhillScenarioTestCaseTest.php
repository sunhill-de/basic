<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\base;
use Tests\CreatesApplication;

class FakeScenario {

    public $flag = 'Empty';
    
    public function SetupBeforeTests() {
        $this->flag .= 'SetupBeforeTests';
        return $this;
    }
    
    public function Setup() {
        $this->flag .= 'Setup';
        return $this;
    }
 
    public function SetTest($test) {
        return $this;
    }
}

class SunhillScenarioTestCaseTest extends SunhillScenarioTestCase
{
   
    static protected $ScenarioClass = 'Sunhill\\Basic\\Tests\\Unit\\FakeScenario';
    
    use CreatesApplication;
    
    public function testSetups() {
        $this->assertEquals('EmptySetupBeforeTestsSetup',$this->GetScenario()->flag);
    }
    
    public function testSetups2() {
        $this->assertEquals('EmptySetupBeforeTestsSetupSetup',$this->GetScenario()->flag);
    }
    
}
