<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDatabase;
use Tests\CreatesApplication;
use Illuminate\Support\Facades\DB;

class ScenarioWithDatabaseTestScenario extends ScenarioBase{

        use ScenarioWithDatabase;
        
        function GetTableDescriptors() {
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
        
        function GetTableContents() {
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

class ScenarioWithDatabaseTest extends SunhillTestCase
{
   
    use CreatesApplication;

    public function testSetupDatabaseGetQuerystr() {
        $test = new ScenarioWithDatabaseTestScenario();
        $this->assertEquals("create table testtable (id int primary key,payload varchar);",
                $this->callProtectedMethod($test,'getQueryStr',array('testtable',
                    ['id int primary key','payload varchar']
                ))
            );
    }
    
    public function testSetupDatabaseSetUpTable() {
        DB::statement('drop table if exists testtable;');
        $test = new ScenarioWithDatabaseTestScenario();
        $this->callProtectedMethod($test,'setupTable',array('testtable',
            ['id int primary key','payload varchar(100)']
        ));
        DB::statement('insert into testtable (id,payload) values (1,"test")');
        $result = DB::table('testtable')->where('id',1)->first();
        $this->assertEquals('test',$result->payload);
    }

    public function testSetupDatabaseComplete() {
        DB::statement('drop table if exists testtable;');
        DB::statement('drop table if exists another;');
        $test = new ScenarioWithDatabaseTestScenario();
        $this->callProtectedMethod($test,'setupDatabase',array());
        DB::statement('insert into testtable (id,name) values (1,"test")');
        DB::statement('insert into another (id,reference,payload) values (1,1,"test")');
        $result = DB::table('testtable')->where('id',1)->first();
        $this->assertEquals('test',$result->name);
        $result = DB::table('another')->where('id',1)->first();
        $this->assertEquals('test',$result->payload);
    }
}
