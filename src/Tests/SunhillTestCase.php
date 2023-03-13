<?php
/**
 * @file SunhillOrchestraTestCasee.php
 * Provides a common basic test for the sunhill project. It expands the laravel basic test for some 
 * helper methods (like accessing protected methods and properties)
 * Lang en
 * Reviewstatus: 2020-18-11
 * Localization: incomplete
 * Documentation: complete
 * Tests: tests\Unit\SunhillOrchestraTestCaseTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Sunhill\Basic\Tests\Constraints\DatabaseHasTableConstraint;
use Tests\CreatesApplication;

abstract class SunhillOrchestraTestCase extends BaseTestCase {

    use SunhillTrait;
    
}
