<?php
/**
 * @file ScenarioWithFiles.php
 * An extension to scenarios that handle handle filesystems
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

trait ScenarioWithDatabase {

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
