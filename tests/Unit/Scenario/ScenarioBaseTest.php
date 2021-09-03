<?php

namespace Sunhill\Basic\Tests\Unit;

use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\SunhillException;
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
        $this->flag .= 'BeforeTestsDestructive';
    }
    
    protected function SetUpBeforeTestsTestUnDestructiveRequirement($descriptor) {
        $this->flag .= 'BeforeTestsUnDestructive';        
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
        $this->assertEquals('BeforeTestsDestructiveBeforeTestsUnDestructive',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSetup($test) {
        $test->Setup();
        $this->assertEquals('BeforeTestsDestructiveBeforeTestsUnDestructiveDU',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSetupDouble($test) {
        $test->Setup();
        $this->assertEquals('BeforeTestsDestructiveBeforeTestsUnDestructiveDUD',$test->flag);
    }
    
    /**
     * @depends testSetupBeforeAll
     * @param unknown $test
     */
    public function testSkipRebuild($test) {
        $test->skipRebuild();
        $test->Setup();
        $this->assertEquals('BeforeTestsDestructiveBeforeTestsUnDestructiveDUD',$test->flag);
        $test->Setup();
        $this->assertEquals('BeforeTestsDestructiveBeforeTestsUnDestructiveDUDD',$test->flag);
    }
    
}
