<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithDatabase;
use Tests\CreatesApplication;
use Illuminate\Support\Facades\DB;

class ScenarioWithDatabaseTestScenario extends ScenarioBase{

        use ScenarioWithDatabase;
        
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
                
}

class ScenarioWithDatabaseTest extends SunhillOrchestraTestCase
{
   
    public function setUp() : void {
        parent::setUp();
        DB::statement('drop table if exists testtable;');
        DB::statement('drop table if exists another;');
    }
    
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
