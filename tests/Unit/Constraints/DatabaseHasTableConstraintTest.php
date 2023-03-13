<?php
/**
 * @file DescriptorHasLinkConstraintTest.php
 * tests the DescriptorHasLinkConstraint
 */
namespace Sunhill\Files\Tests\Unit\Constraints;

use Sunhill\Basic\Tests\Constraints\DatabaseHasTableConstraint;
use Sunhill\Basic\Tests\SunhillOrchestraTestCase;
use Illuminate\Support\Facades\DB;

class DatabaseHasTableConstraintTest extends SunhillOrchestraTestCase
{
   
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
