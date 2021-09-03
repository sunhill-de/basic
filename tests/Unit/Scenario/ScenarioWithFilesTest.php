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
        
}

class ScenarioWithFilesTest extends SunhillTestCase
{
   
    use CreatesApplication;

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
    
}
