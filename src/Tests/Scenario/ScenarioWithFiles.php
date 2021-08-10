<?php
/**
 * @file ScenarioWithFiles.php
 * An extension to scenarios that handle handle filesystems
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: none required
 * Documentation: complete
 * Tests: tests/Unit/ScenatiosWithFilesTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

trait ScenarioWithFiles {

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
        return $this->target;
    }
    
    /**
     * Prepends a dir with the target
     * @param string $subpath
     * @return string
     */
    protected function GetCompletePath(string $subpath) {
        return $this->target.$subpath;    
    }
    
    /**
     * Wipes out all files, dirs and links in the target
     */
    protected function ClearTarget() {
        exec("rm -rf ".$this->GetTarget().'/*');
    }
    
    protected function SetUpFiles() {
        $descriptors = $this->GetFiles();
        foreach ($descriptors as $table => $descriptor) {
            
        }
    }
    
    protected function SetUpDirs() {
        $descriptors = $this->GetDirs();
        foreach ($descriptors as $dir) {
            
        }        
    }
    
    protected function SetUpLinks() {
        $descriptors = $this->GetLinks();
        foreach ($descriptors as $link) {
            
        }
    }
    
    abstract function GetFiles();
    abstract function GetDirs();        
    abstract function GetLinks();
}
