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
                ['path'=>'/test.txt','content'=>'ABCD'],
                ['path'=>'/test/test.txt','content'=>'AAAA'],
                ['path'=>'/test/subdir/test.txt','content'=>'BBB'],                
            ];
        }
        
        protected function GetDirs() {
            return [
                '/test',
                '/test/subdir',
                '/test/subdir2',                
            ];
        }
        
        protected function GetLinks() {
            return [
                ['link'=>'/link.txt','target'=>'test.txt'],
                ['link'=>'/test/subdir/link.txt','target'=>'../../test.txt']
            ];
        }
        
}

class ScenarioWithFilesTest extends SunhillTestCase
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
    
    public function testSetupFile() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupFile',['/test.txt','ABCDEF']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test.txt')));
    }

    /**
     * This is a feature test
     */
    public function testSetupFiles() {
        $test = $this->SetupScenario();
        exec('mkdir -p '.storage_path().'/temp/test/subdir');
        $this->callProtectedMethod($test,'SetupFiles',[]);
        $this->assertEquals('ABCD',file_get_contents(storage_path('/temp/test.txt')));
        $this->assertEquals('AAAA',file_get_contents(storage_path('/temp/test/test.txt')));
        $this->assertEquals('BBB',file_get_contents(storage_path('/temp/test/subdir/test.txt')));
    }
    
    public function testSetupLink_absolute() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupFile',['/test.txt','ABCDEF']);
        
        $this->callProtectedMethod($test,'SetupLink',['/link.txt','/test.txt']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/link.txt')));
    }
    
    public function testSetupLink_relative() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupFile',['/test.txt','ABCDEF']);
        exec('mkdir -p '.storage_path().'/temp/test/subdir');
        
        $this->callProtectedMethod($test,'SetupLink',['/test/subdir/link.txt','../../test.txt']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test/subdir/link.txt')));
    }
    
    public function testSetupLinks() {
        $test = $this->SetupScenario();
        $this->callProtectedMethod($test,'SetupFile',['/test.txt','ABCDEF']);
        exec('mkdir -p '.storage_path().'/temp/test/subdir');
        
        $this->callProtectedMethod($test,'SetupLinks',[]);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/link.txt')));
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test/subdir/link.txt')));
    }
    
}
