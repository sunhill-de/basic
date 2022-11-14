<?php
/**
 * @file SunhillTestCasee.php
 * Provides a common basic test for the sunhill project. It expands the laravel basic test for some 
 * helper methods (like accessing protected methods and properties)
 * Lang en
 * Reviewstatus: 2020-18-11
 * Localization: incomplete
 * Documentation: complete
 * Tests: tests\Unit\SunhillTestCaseTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests;

use Orchestra\Testbench\TestCase;

abstract class SunhillTestbenchTestCase extends TestCase {

    use SunhillTrait;
        
}
