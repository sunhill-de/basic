<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Tests\CreatesApplication;

class ScenarioWithFilesTestScenario extends ScenarioBase{

        use ScenarioWithFiles;
        
        protected function GetFiles() {
            return [
                
            ];
        }
        
        protected function GetDirs() {
            return [
                
            ];
        }
        
        protected function GetLinks() {
            return [
                
            ];
        }
        
}

class ScenarioWithFilesTest extends SunhillTestCase
{
   
    use CreatesApplication;

    protected function SetupScenario() {
        $test = new ScenarioWithFilesTestScenario();
        $this->callProtectedMethod($test,'SetTarget',[$this->GetTempDir()]);
        return $test;
    }
    
    public function testTarget() {
        $test = new ScenarioWithFilesTestScenario();
        $this->callProtectedMethod($test,'SetTarget',['/target/dir']);
        $this->assertEquals('/target/dir',$this->callProtectedMethod($test,'GetTarget',[]));
    }
    
    public function testGetCompletePath() {
        $test = new ScenarioWithFilesTestScenario();
        $this->callProtectedMethod($test,'SetTarget',['/target/dir']);
        $this->assertEquals('/target/dir/sub/dir',$this->callProtectedMethod($test,'GetCompletePath',['/sub/dir']));
    }
    
    public function testClearTarget() {
        $test = $this->SetupScenario();
        exec('touch '.$this->GetTempDir().'/test.txt');
        $this->assertTrue(file_exists($this->GetTempDir().'/test.txt'));
        $this->callProtectedMethod($test,'ClearTarget',[]);
        $this->assertFalse(file_exists($this->GetTempDir().'/test.txt'));
    }
}
