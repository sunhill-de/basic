<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithLinks;
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
        exec("mkdir -p ".$this->GetTempDir().'/test/subdir');
        $file = fopen($this->GetTempDir().'/test.txt','w+');
        fputs($file,'ABCDEF');
        fclose($file);
    }
    
    public function testSetupLink_absolute() {        
        $test = new ScenarioWithLinksTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupLink',['/link.txt','/test.txt']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/link.txt')));
    }
    
    public function testSetupLink_relative() {        
        $test = new ScenarioWithLinksTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupLink',['/test/subdir/link.txt','../../test.txt']);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test/subdir/link.txt')));
    }
    
    public function testSetupLinks() {
        $test = new ScenarioWithLinksTestScenario();
        $test->setTest($this);
        $this->callProtectedMethod($test,'SetupLinks',[]);
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/link.txt')));
        $this->assertEquals('ABCDEF',file_get_contents(storage_path('/temp/test/subdir/link.txt')));
    }
    
}
