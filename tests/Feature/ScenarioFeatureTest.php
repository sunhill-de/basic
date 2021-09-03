<?php

namespace Sunhill\Basic\Tests\Feature;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Tests\CreatesApplication;

class ScenarioFeatureTestScenario extends ScenarioBase{

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

class ScenarioFeatureTest extends SunhillTestCase
{
   
    use CreatesApplication;
}
