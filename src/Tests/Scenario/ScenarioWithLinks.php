<?php
/**
 * @file ScenarioWithLinks.php
 * An extension to scenarios that handle links
 * Lang en
 * Reviewstatus: 2021-09-02
 * Localization: none required
 * Documentation: complete
 * Tests: tests/Unit/ScenatiosWithFilesTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

trait ScenarioWithLinks {
    
    use ScenarioFileHelper;
    
    protected function SetUpLinks() {
        $descriptors = $this->GetLinks();
        foreach ($descriptors as $link) {
            $this->SetupLink($link['link'],$link['target']);
        }
    }
    
    protected function SetupLink($link,$target) {
        $link = $this->GetCompletePath($link);
        if (realpath($link)) {
            exec("rm -rf $link");
        }
        if (substr($target,0,2) !== '..') {
            // absolute link
            $target = $this->GetCompletePath($target);
        }
        exec("ln -s '".$target."' '".$link."'");
    }
    
    abstract function GetLinks();
}
