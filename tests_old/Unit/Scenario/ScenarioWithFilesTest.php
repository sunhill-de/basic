<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
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
        
}

class ScenarioWithFilesTest extends SunhillOrchestraTestCase
{
   
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
        exec("mkdir ".$this->GetTempDir().'/subdir');
    }
    
    public function testSetupFile() {
        $test = new ScenarioWithFilesTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupFile',['/test.txt','ABCDEF']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test.txt')));
    }

    /**
     * This is a feature test
     */
    public function testSetupFiles() {
        $test = new ScenarioWithFilesTestScenario();
        $test->setTest($this);
        exec('mkdir -p '.storage_path().'/temp/test/subdir');
        $this->callProtectedMethod($test,'SetupFiles',[]);
        $this->assertEquals('ABCD',file_get_contents(storage_path('/temp/test.txt')));
        $this->assertEquals('AAAA',file_get_contents(storage_path('/temp/test/test.txt')));
        $this->assertEquals('BBB',file_get_contents(storage_path('/temp/test/subdir/test.txt')));
    }
    
}
