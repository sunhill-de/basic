<?php
/**
 * @file ScenarioWithDirs.php
 * An extension to scenarios that handle dirs
 * Lang en
 * Reviewstatus: 2021-09-02
 * Localization: none required
 * Documentation: complete
 * Tests: tests/Unit/ScenatiosWithFilesTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

trait ScenarioWithDirs {
    
    use ScenarioFileHelper;
    
    protected function SetUpDirs() {
        $descriptors = $this->GetDirs();
        foreach ($descriptors as $dir) {
            $this->SetupDir($dir);
        }        
    }
    
    protected function SetupDir(string $dir) {
        $dir = $this->GetCompletePath($dir);
        if (file_exists($dir)) {
            exec("rm -rf $dir");
        }
        exec('mkdir '.$dir);
    }
    
    abstract function GetDirs();        
}
