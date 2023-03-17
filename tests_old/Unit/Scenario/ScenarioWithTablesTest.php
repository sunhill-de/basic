<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\SunhillException;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Sunhill\Basic\Tests\Scenario\ScenarioWithTables;
use Tests\CreatesApplication;
use Illuminate\Support\Facades\DB;

class ScenarioWithTablesTestScenario extends ScenarioBase{

        use ScenarioWithTables;
        
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

class ScenarioWithTablesTest extends SunhillOrchestraTestCase
{
   
     public function setUp() : void {
         parent::setUp();
         DB::statement('drop table if exists testtable;');
         DB::statement('drop table if exists another;');
         DB::statement('create table testtable (id int auto_increment primary key,name varchar(100))');
         DB::statement('create table another (id int auto_increment primary key,reference int,payload varchar(20))');
     }

    public function testGetReferencePass() {
        $test = new ScenarioWithTablesTestScenario();
        $this->setProtectedProperty($test,'references',['TEST'=>1]);
        $this->assertEquals(1,$this->callProtectedMethod($test,'getReference',['=>TEST']));
    }

    public function testGetReferencePassWithArray() {
        $test = new ScenarioWithTablesTestScenario();
        $this->setProtectedProperty($test,'references',['TEST'=>1]);
        $this->assertEquals(1,$this->callProtectedMethod($test,'getReference',['=>TEST->id']));
    }
    
    public function testGetReferenceFail() {
        $this->expectException(\Exception::class);
        $test = new ScenarioWithTablesTestScenario();
        $this->setProtectedProperty($test,'references',['TEST'=>1]);
        $this->assertEquals(1,$this->callProtectedMethod($test,'getReference',['=>NONEXISTING']));
    }

    /**
     * @dataProvider GetInsertQueryStrProvider
     * @param unknown $values
     * @param unknown $expect
     */
    public function testGetInsertQueryStr($values,$expect) {
        $test = new ScenarioWithTablesTestScenario();
        $this->setProtectedProperty($test,'references',['TEST'=>111]);
        $this->assertEquals($expect,$this->callProtectedMethod($test,'getInsertQueryStr',['testtable',['a','b'],$values]));
    }
    
    public function GetInsertQueryStrProvider() {
        return [
            [[1,2],"insert into testtable (a,b) values ('1','2')"],
            [["ABC",2],"insert into testtable (a,b) values ('ABC','2')"],
            [["=>TEST",2],"insert into testtable (a,b) values ('111','2')"],
        ];
    }
    
    public function testInsertSingleValue_simple() {
        DB::statement('drop table if exists testtable;');
        DB::statement('create table testtable (a varchar(2),b varchar(2))');
        $test = new ScenarioWithTablesTestScenario();
        $this->callProtectedMethod($test,'insertSingleValue',['testtable',['a','b'],1,[1,2]]);
        $result = DB::table('testtable')->first();
        $this->assertEquals(1,$result->a);
        $this->assertFalse(isset($this->getProtectedProperty($test,'references')[1]));
    }
    
    public function testInsertSingleValue_withreference() {
        DB::statement('drop table if exists testtable;');
        DB::statement('create table testtable (id int auto_increment primary key,a varchar(2),b varchar(2))');
        $test = new ScenarioWithTablesTestScenario();
        $this->callProtectedMethod($test,'insertSingleValue',['testtable',['a','b'],'TESTINSERT',[1,2]]);
        $result = DB::table('testtable')->first();
        $this->assertEquals(1,$this->getProtectedProperty($test,'references')['TESTINSERT']);
    }
    
    public function testFillTable() {
        DB::statement('drop table if exists testtable;');
        DB::statement('create table testtable (id int auto_increment primary key,a varchar(2),b varchar(2))');
        $test = new ScenarioWithTablesTestScenario();
        $this->callProtectedMethod($test,'filltable',['testtable',[['a','b'],[
           [1,2],
            'reference'=>[3,4],
            'another'=>['=>reference',6]
        ]]]);
        $result = DB::table('testtable')->where('id',1)->first();
        $this->assertEquals(1,$result->a);
        $result = DB::table('testtable')->where('id',2)->first();
        $this->assertEquals(3,$result->a);
        $result = DB::table('testtable')->where('id',3)->first();
        $this->assertEquals(2,$result->a);
    }
    
    public function testFillTable_withNull() {
        DB::statement('drop table if exists testtable;');
        DB::statement('create table testtable (id int auto_increment primary key,a varchar(2),b int)');
        $test = new ScenarioWithTablesTestScenario();
        $this->callProtectedMethod($test,'filltable',['testtable',[['a','b'],[
            [NULL,2],
            ['A',NULL],
            ['A','NULL'],
            ['A','=>NULL'],
        ]]]);
        $result = DB::table('testtable')->where('id',1)->first();
        $this->assertEquals(null,$result->a);
        $result = DB::table('testtable')->where('id',2)->first();
        $this->assertEquals(null,$result->b);
        $result = DB::table('testtable')->where('id',3)->first();
        $this->assertEquals(null,$result->b);
        $result = DB::table('testtable')->where('id',4)->first();
        $this->assertEquals(null,$result->b);
    }
    
}
