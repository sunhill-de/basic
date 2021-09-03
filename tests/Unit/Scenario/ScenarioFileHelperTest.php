<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioFileHelper;
use Tests\CreatesApplication;

class ScenarioFileHelpersScenario extends ScenarioBase{

    use ScenarioFileHelper;
}

class ScenarioFileHelperTest extends SunhillTestCase
{
   
    use CreatesApplication;

    /**
     * Depends on a functioning SetTarget and ClearTarget
     * @return \Sunhill\Basic\Tests\Unit\ScenarioFileHelpersScenario
     */
    protected function SetupScenario() {
        $test = new ScenarioFileHelpersScenario();
        $this->callProtectedMethod($test,'SetTarget',[$this->GetTempDir()]);
        $this->callProtectedMethod($test,'ClearTarget',[]);
        return $test;
    }
    
    public function testTarget() {
        $test = new ScenarioFileHelpersScenario();
        $this->callProtectedMethod($test,'SetTarget',['/target/dir']);
        $this->assertEquals('/target/dir',$this->callProtectedMethod($test,'GetTarget',[]));
    }
    
    public function testGetCompletePath() {
        $test = new ScenarioFileHelpersScenario();
        $this->callProtectedMethod($test,'SetTarget',['/target/dir']);
        $this->assertEquals('/target/dir//sub/dir',$this->callProtectedMethod($test,'GetCompletePath',['/sub/dir']));
    }
    
    public function testClearTarget() {
        $test = new ScenarioFileHelpersScenario();
        $this->callProtectedMethod($test,'SetTarget',[$this->GetTempDir()]);
        exec('touch '.$this->GetTempDir().'/test.txt');
        $this->assertTrue(file_exists($this->GetTempDir().'/test.txt'));
        $this->callProtectedMethod($test,'ClearTarget',[]);
        $this->assertFalse(file_exists($this->GetTempDir().'/test.txt'));
    }
            
}
