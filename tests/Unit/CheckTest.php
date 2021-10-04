<?php

namespace Sunhill\Basic\Tests\Unit;

/**
 * @file CheckTest.php
 * lang: en
 * dependencies: FilemanagerTestCase
 */
use Sunhill\Basic\Tests\SunhillTestCase;
use Sunhill\Basic\Tests\Unit\CreatesApplication;
use Sunhill\Basic\Checker\Checker;
use Sunhill\Basic\Facades\Checks;
use Sunhill\Basic\Utils\Descriptor;
use Sunhill\Basic\Checker\CheckException;

class test_checker extends checker {
    
    public function checkSomething() {
        return $this->create_result('OK','Check something');
    }
    
}

class fail_checker extends checker {
    
    public function checkSomethingFailing() {
        return $this->create_result('FAILED','Check something failing','Something went wrong');
    }
}

class CheckTest extends SunhillTestCase
{

    use CreatesApplication;
    
    public function testNoCheckerInstalled() {
        Checks::Purge();
        $this->expectException(CheckException::class);
        Checks::Check();
    }
    
    public function testInstallChecker() {
        Checks::Purge();
        Checks::InstallChecker(test_checker::class);
        $result = Checks::Check();
        $this->assertEquals('OK',$result[0]->result);
        $this->assertEquals('Check something',$result[0]->name);
    }
    
    public function testCheckFail() {
        Checks::Purge();
        Checks::InstallChecker(test_checker::class);
        Checks::InstallChecker(fail_checker::class);        
        $result = Checks::Check();
        $this->assertEquals('FAILED',$result[1]->result);
        $this->assertEquals('Something went wrong',$result[1]->error);
    }
}
