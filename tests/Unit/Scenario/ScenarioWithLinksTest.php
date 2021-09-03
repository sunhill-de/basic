<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Tests\CreatesApplication;

class ScenarioWithLinksTestScenario extends ScenarioBase{

        use ScenarioWithLinks;
        
        protected function GetLinks() {
            return [
                ['link'=>'/link.txt','target'=>'test.txt'],
                ['link'=>'/test/subdir/link.txt','target'=>'../../test.txt']
            ];
        }
        
}

class ScenarioWithLinksTest extends SunhillTestCase
{
   
    use CreatesApplication;

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
