<?php

namespace Sunhill\Basic\Tests\Feature;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;

use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithLinks;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDatabase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;

use Tests\CreatesApplication;

class ScenarioDestructiveFeatureTestScenario extends ScenarioBase{

        use ScenarioWithFiles;
        use ScenarioWithDirs;
        use ScenarioWithLinks;
        use ScenarioWithDatabase;
        use ScenarioWithTables;
        
    protected $Requirements = [
        'Dirs'=>[
            'destructive'=>true,
        ],        
        'Files'=>[
            'destructive'=>true,
        ],
        'Links'=>[
            'destructive'=>true,
        ],        
        'Database'=>[
            'destructive'=>true,
        ],        
        'Tables'=>[
            'destructive'=>true,
        ],        
    ];
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

        function GetDatabase() {
            return [
                'testtable'=>[
                    'id int auto_increment primary key',
                    'name varchar(100)'
                ],
                'another'=>[
                    'id int auto_increment primary key',
                    'reference int',
                    'payload varchar(20)'
                ]
            ];
        }

        function GetTables() {
            return [
                'testtable'=>[
                    ['name'],
                    [
                        'reference1'=>['TestA'],
                        ['TestB'],
                        ['TestC'],
                        'reference2'=>['TestD']
                    ]
                ],
                'another'=>[
                    ['reference','payload'],
                    [
                        ['=>reference1->id','Payload1'],
                        ['=>reference2','Payload2'],
                        ['=>NULL','Payload3'],
                    ]
                ]
            ];
        }
                
}

class ScenarioDestructiveFeatureTest extends SunhillScenarioTestCase
{
   
    protected static $ScenarioClass = 'Sunhill\\Basic\\Tests\\Feature\\ScenarioDestructiveFeatureTestScenario'; 
        
    use CreatesApplication;
    
    public function testInitialState() {
    }        
}
