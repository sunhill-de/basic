<?php
/**
 * @file ScenarioBase.php
 * A scnario if a collection of reusable fixtures for sunhill tests
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

use Sunhill\Basic\SunhillException;

class ScenarioBase {
    
    protected $test; /**<< Stores a back reference to the test */

    protected $Requirements = [
            
    ]; /**<< Stores the requirements for this scenario as an associative array. The key defines the requirement base name
    and the value is the Descriptor of the requirement */

    protected $Setups = [
        
    ]; /**<< Stores, what requirements are already setup */

    protected $skipRebuild=false;
    
    /**
     * Sets a reference to the test itself
     * @param unknown $test
     */
    public function setTest($test) {
        $this->test = $test;
        return $this;
    }
 
    /**
     * Gets a reference to the test itself
     * @return The calling test
     */
    public function getTest() {
        return $this->test;
    }
    
    protected function WalkRequirements($callback_prefix,$only_if_uninitialized = false) {
        foreach ($this->Requirements as $requirement => $Descriptor) {
            $callback = $callback_prefix.$requirement;
            if (method_exists($this,$callback)) {
                // Call this callback only if $only_if_uninitialied is false or the requirement
                // is not initializes yet
                if (!$only_if_uninitialized || !in_array($requirement,$this->Setups)) {
                    $this->$callback($Descriptor);
                    if ($only_if_uninitialized) {
                        if (!$Descriptor['destructive']) {
                            $this->Setups[] = $requirement;
                        }
                    }
                }
            }
        }        
    }
    
    /**
     * This method is called before any test is run
     */
    public function SetupBeforeTests() {
        $this->WalkRequirements('SetupBeforeTests');
    }
    
    /**
     * This method is called befone any test
     */
    public function Setup() 
    {
        if (!$this->skipRebuild) {
            $this->WalkRequirements('Setup',true);
        }
        $this->skipRebuild = false;
    }
    
    public function skipRebuild() 
    {
        $this->skipRebuild = true;
    }
    
    public function getScenarioValue(string $identifier)
    {
        throw new SunhillException("Unknown ScenarioIdentifier '$identifier'");    
    }
}
