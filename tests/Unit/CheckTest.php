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

class test_checker extends checker 
{
    
    public function checkSomething() {
        return $this->createResult('OK','Check something');
    }
    
}

class fail_checker extends checker 
{
    
    public function checkSomethingFailing() {
        return $this->createResult('FAILED','Check something failing','Something went wrong');
    }
}

class CheckTest extends SunhillTestCase
{

    use CreatesApplication;
    
    public function testNoCheckerInstalled() 
    {
        Checks::purge();
        $this->expectException(CheckException::class);
        Checks::check();
    }
    
    public function testInstallChecker() 
    {
        Checks::purge();
        Checks::installChecker(test_checker::class);
        $result = Checks::check();
        $this->assertEquals('OK',$result[0]->result);
        $this->assertEquals('Check something',$result[0]->name);
    }
    
    public function testCheckFail() 
    {
        Checks::Purge();
        Checks::installChecker(test_checker::class);
        Checks::installChecker(fail_checker::class);        
        $result = Checks::check();
        $this->assertEquals('FAILED',$result[1]->result);
        $this->assertEquals('Something went wrong',$result[1]->error);
    }
    
}
