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
        foreach ($descriptors as $descriptor) {
           $this->SetupFile($descriptor['path'],$descriptor['content']); 
        }
    }
    
    protected function SetupFile(string $path,string $content) {
        $path = $this->GetCompletePath($path);
        $file = fopen($path,'w+');
        fputs($file,$content);
        fclose($file);
    }
    
    protected function SetUpDirs() {
        $descriptors = $this->GetDirs();
        foreach ($descriptors as $dir) {
            $this->SetupDir($dir);
        }        
    }
    
    protected function SetupDir(string $dir) {
        $dir = $this->GetCompletePath($dir);
        exec('mkdir '.$dir);
    }
    
    protected function SetUpLinks() {
        $descriptors = $this->GetLinks();
        foreach ($descriptors as $link) {
            $this->SetupLink($link['link'],$link['target']);
        }
    }
    
    protected function SetupLink($link,$target) {
        $link = $this->GetCompletePath($link);
        if (substr($target,0,2) !== '..') {
            // absolute link
            $target = $this->GetCompletePath($target);
        }
        exec("ln -s '".$target."' '".$link."'");
    }
    
    abstract function GetFiles();
    abstract function GetDirs();        
    abstract function GetLinks();
}
