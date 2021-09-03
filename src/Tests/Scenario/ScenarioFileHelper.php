<?php
/**
 * @file ScenarioFileHelper.php
 * Some common method for file manipulating scenarios
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: none required
 * Documentation: complete
 * Tests: tests/Unit/ScenatiosWithFilesTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

trait ScenarioFileHelper {
    
    protected $target; /**<< Stores a prefix for all file dirs */
    
    /**
     * Sets the target (the prefix for all directories) to a new value
     * @param string $path
     * @return \Sunhill\Basic\Tests\Scenario\ScenarioWithFiles
     */
    protected function SetTarget(string $path) {
        $this->target = $path;
        return $this;
    }
    
    /**
     * Returns the target
     * @return unknown
     */
    protected function GetTarget() {
        if (is_null($this->target)) {
            return $this->getTest()->GetTempDir();   
        } else {
            return $this->target;
        }
    }
    
    /**
     * Prepends a dir with the target
     * @param string $subpath
     * @return string
     */
    protected function GetCompletePath(string $subpath) {
        return $this->GetTarget().$subpath;    
    }
    
    /**
     * Wipes out all files, dirs and links in the target
     */
    protected function ClearTarget() {
        exec("rm -rf ".$this->GetTarget().'/*');
    }
    
}
