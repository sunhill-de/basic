<?php

namespace Sunhill\Basic\Tests\Feature;

use Sunhill\Basic\Tests\SunhillScenarioTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;

use Sunhill\Basic\Tests\Scenario\ScenarioWithFiles;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDirs;
use Sunhill\Basic\Tests\Scenario\ScenarioWithLinks;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDatabase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;

use Tests\CreatesApplication;
use Illuminate\Support\Facades\DB;


class ScenarioNonDestructiveFeatureTestScenario extends ScenarioBase{

        use ScenarioWithFiles,ScenarioWithDirs,ScenarioWithLinks,
            ScenarioWithDatabase,ScenarioWithTables;
        
    protected $Requirements = [
        'Dirs'=>[
            'destructive'=>false,
        ],        
        'Files'=>[
            'destructive'=>false,
        ],
        'Links'=>[
            'destructive'=>false,
        ],        
        'Database'=>[
            'destructive'=>false,
        ],        
        'Tables'=>[
            'destructive'=>false,
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

class ScenarioNonDestructiveFeatureTest extends SunhillScenarioTestCase
{
           
    use CreatesApplication;
    
    protected function GetScenarioClass() {
        return ScenarioNonDestructiveFeatureTestScenario::class;
    }
    
    public function testInitialState() {
        $this->assertTrue(file_exists($this->GetTempDir().'/test/subdir'));
        $this->assertTrue(file_exists($this->GetTempDir().'/test/subdir/test.txt'));
        $this->assertTrue(file_exists($this->GetTempDir().'/test/subdir/link.txt'));
        $this->assertDatabaseHas('another',['reference'=>1,'payload'=>'Payload1']);
    }
    
    public function testDestroy() {
        exec('rm -rf '.$this->GetTempDir().'/test/subdir');
        DB::table('another')->where('payload','Payload1')->delete();
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir'));
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir/test.txt'));
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir/link.txt'));
        $this->assertDatabaseMissing('another',['payload'=>'Payload1']);
    }
    
    public function testRestrore() {
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir'));
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir/test.txt'));
        $this->assertFalse(file_exists($this->GetTempDir().'/test/subdir/link.txt'));
        $this->assertDatabaseMissing('another',['payload'=>'Payload1']);
    }
        
}
