<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Sunhill\Basic\Tests\Scenario\ScenarioBase;
use Tests\CreatesApplication;
use Sunhill\Basic\SunhillException;

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
    
    protected function SetUpBeforeTestsTestDestructiveRequirement($Descriptor) {
        $this->flag .= 'BD';
    }
    
    protected function SetUpBeforeTestsTestUnDestructiveRequirement($Descriptor) {
        $this->flag .= 'BU';        
    }
    
    protected function SetUpTestDestructiveRequirement($Descriptor) {
        $this->flag .= 'D';        
    }
    
    protected function SetUpTestUnDestructiveRequirement($Descriptor) {
        $this->flag .= 'U';        
    }
    
    public function getScenarioValue(string $identifier)
    {
        switch ($identifier) {
            case'test':
                return 'TEST';
                break;
            default:
                return parent::getScenarioValue($identifier);
        }
    }
}

class ScenarioBaseTest extends SunhillOrchestraTestCase
{
   
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
    
    public function testGetScenarioValuePass() 
    {
        $test = new ScenarioBaseTestScenario();
        $this->assertEquals('TEST',$test->getScenarioValue('test'));
    }
    
    public function testGetScenarioValueFail() 
    {
        $test = new ScenarioBaseTestScenario();
        $this->expectException(SunhillException::class);
        $test->getScenarioValue('notexisting');
    }
    
}
