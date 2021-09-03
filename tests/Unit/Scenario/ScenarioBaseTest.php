<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Tests\CreatesApplication;

class ScenarioBaseTestScenario extends ScenarioBase{

    public $flag = '';
    
    protected $Requirements = [
        'TestDestructiveRequirement'=>[
            'destructive'=>true,
        ],
        'TestUnDestructiveRequirement'=>[
            'destructive'=>false,
        ],        
    ];
    
    protected function SetUpBeforeTestsTestDestructiveRequirement($descriptor) {
        $this->flag .= 'BD';
    }
    
    protected function SetUpBeforeTestsTestUnDestructiveRequirement($descriptor) {
        $this->flag .= 'BU';        
    }
    
    protected function SetUpTestDestructiveRequirement($descriptor) {
        $this->flag .= 'D';        
    }
    
    protected function SetUpTestUnDestructiveRequirement($descriptor) {
        $this->flag .= 'U';        
    }
        
}

class ScenarioBaseTest extends SunhillTestCase
{
   
    use CreatesApplication;

    public function testSetupBeforeAll() {
        $test = new ScenarioBaseTestScenario();
        $this->assertEquals('',$test->flag);
        return $test;
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSetupBeforeTests($test) {
        $test->SetupBeforeTests();
        $this->assertEquals('BDBU',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSetup($test) {
        $test->Setup();
        $this->assertEquals('BDBUDU',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSetupDouble($test) {
        $test->Setup();
        $this->assertEquals('BDBUDUD',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSkipRebuild($test) {
        $test->skipRebuild();
        $test->Setup();
        $this->assertEquals('BDBUDUD',$test->flag);
        $test->Setup();
        $this->assertEquals('BDBUDUDD',$test->flag);
    }
    
}
