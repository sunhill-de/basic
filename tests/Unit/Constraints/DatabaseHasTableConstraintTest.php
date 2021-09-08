<?php
/**
 * @file DescriptorHasLinkConstraintTest.php
 * tests the DescriptorHasLinkConstraint
 */
namespace Sunhill\Files\Tests\Unit\Constraints;

use Illuminate\Foundation\Testing\TestCase;
use Sunhill\Basic\Tests\Constraints\DatabaseHasTableConstraint;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;

class DatabaseHasTableConstraintTest extends TestCase
{

    use CreatesApplication;
    
    public function testDatabaseHasTableFail() {
       DB::statement('drop table if exists testtable');
       $constraint = new DatabaseHasTableConstraint();
       $this->assertFalse($constraint->matches('testtable'));
    }
    
    public function testDatabaseHasTablePass() {
        DB::statement('create table testtable (id int)');
        $constraint = new DatabaseHasTableConstraint();
        $this->assertTrue($constraint->matches('testtable'));
        DB::statement('drop table if exists testtable');
    }
}
