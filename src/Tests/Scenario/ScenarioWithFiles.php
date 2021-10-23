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
    
    use ScenarioFileHelper;
    
    protected function SetUpFiles() {
        $descriptors = $this->GetFiles();
        foreach ($descriptors as $Descriptor) {
           $this->SetupFile($Descriptor['path'],$Descriptor['content']); 
        }
    }
    
    protected function SetupFile(string $path,string $content) {
        $path = $this->GetCompletePath($path);
        if (file_exists($path)) {
            exec("rm -rf $path");
        }
        $file = fopen($path,'w+');
        fputs($file,$content);
        fclose($file);
    }
    
    abstract function GetFiles();
}
