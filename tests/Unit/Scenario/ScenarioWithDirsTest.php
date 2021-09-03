<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Tests\CreatesApplication;

class ScenarioWithDirsTestScenario extends ScenarioBase{

        use ScenarioWithDirs;
        
        protected function GetDirs() {
            return [
                '/test',
                '/test/subdir',
                '/test/subdir2',                
            ];
        }
                
}

class ScenarioWithDirsTest extends SunhillTestCase
{
   
    use CreatesApplication;

    /**
     * Depends on a functioning SetTarget and ClearTarget
     * @return \Sunhill\Basic\Tests\Unit\ScenarioWithFilesTestScenario
     */
    protected function SetupScenario() {
        $test = new ScenarioWithFilesTestScenario();
        $this->callProtectedMethod($test,'SetTarget',[$this->GetTempDir()]);
        $this->callProtectedMethod($test,'ClearTarget',[]);
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
        $test = new ScenarioWithFilesTestScenario();
        $this->callProtectedMethod($test,'SetTarget',[$this->GetTempDir()]);
        exec('touch '.$this->GetTempDir().'/test.txt');
        $this->assertTrue(file_exists($this->GetTempDir().'/test.txt'));
        $this->callProtectedMethod($test,'ClearTarget',[]);
        $this->assertFalse(file_exists($this->GetTempDir().'/test.txt'));
    }
    
    public function testSetupDir() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupDir',['/test']);
        $this->assertTrue(is_dir(storage_path('/temp/test')));
    }
    
    /**
     * This is a feature test
     */
    public function testSetupDirs() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupDirs',[]);
        $this->assertTrue(is_dir(storage_path('/temp/test/subdir')));
        $this->assertTrue(is_dir(storage_path('/temp/test/subdir2')));
    }
        
}
