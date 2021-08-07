<?php
/**
 * @file SunhillScenarioTestCasee.php
 * Provides a testsuite that is capable of initiating a scenario
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class SunhillScenarioTestCase extends SunhillTestCase {

    protected static $ScenarioClass = ''; /**<< Here the class name of the scenario should be entered **/
    
    protected static $Scenario=null; 
    
    /** 
     * This method creates a scenario (if one is defined) and calls SetupBeforeTests for this scenario
     * @return The created scenario or null if none is defined
     */
    protected function CreateScenario() {
        if (!empty(static::$ScenarioClass)) {
            $class_name = static::$ScenarioClass;
            static::$Scenario = new $class_name();
            static::$Scenario->SetupBeforeTests();
            return static::$Scenario;
        }
    }
    
    /**
     * Returns the current scenario (or creates one if it wasn't created yet)
     * @return The created scenario or null if none is defined
     */
    protected function GetScenario() {
        if (empty(static::$Scenario)) {
            return $this->CreateScenario();
        } else {
            return static::$Scenario;
        }
    }
    
    public function setUp() : void {
        parent::setUp();
        $this->GetScenario()->SetTest($this)->Setup();
    }
    
}
