<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
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

    public function setUp() : void {
        parent::setUp();
        $d = dir($this->GetTempDir());
        while (false !== ($entry = $d->read())) {
            if (($entry !== '.') && ($entry !== '..')) {
                $command = 'rm -rf '.$this->GetTempDir().'/'.$entry;
                exec("rm -rf ".$this->GetTempDir().'/'.$entry);
            }
        }
        $d->close();
        
    }

    public function testSetupDir() {
        $test = new ScenarioWithDirsTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupDir',['/test']);
        $this->assertTrue(is_dir(storage_path('/temp/test')));
    }
    
    /**
     * This is a feature test
     */
    public function testSetupDirs() {
        $test = new ScenarioWithDirsTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupDirs',[]);
        $this->assertTrue(is_dir(storage_path('/temp/test/subdir')));
        $this->assertTrue(is_dir(storage_path('/temp/test/subdir2')));
    }
        
}
