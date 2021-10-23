<?php
/**
 * @file ScenarioWithDatabase.php
 * An extension to scenarios that handle handle the creation of database tables
 * Lang en
 * Reviewstatus: 2021-09-01
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

use Illuminate\Support\Facades\DB;

trait ScenarioWithDatabase {
    
    /**
     * Creates all the tables that are defined in the GetTableDescriptors function
     */
    protected function SetUpDatabase() {
        $this->references = [];
        $descriptors = $this->GetDatabase();
        if (empty($descriptors)) {
            return; // Perhaps no new tables have to be created
        }
        if (!is_array($descriptors)) {
            // We expect an array as a function result, if not this is an error
            throw \Exception("GetDatabase doesn't return an array");
        }
        foreach ($descriptors as $table => $Descriptor) {
            $this->setupTable($table,$Descriptor);
        }
    }
    
    protected function setupTable(string $tablename,array $Descriptor) {
        // Descriptor should be an array of string with the column definition of the table
        DB::statement("drop table if exists $tablename");
        $query_str = $this->GetQueryStr($tablename,$Descriptor);
        DB::statement($query_str);
    }
    
    /**
     * Create the mysql query string that creates the given table
     * @param string $tablename
     * @param array $Descriptor
     * @return string
     */
    protected function getQueryStr(string $tablename,array $Descriptor) {
        $query_str = "create table $tablename (";
        $first = true;
        foreach ($Descriptor as $line) {
            $query_str .= ($first?'':',').$line;
            $first = false;
        }
        return $query_str.');';
    }
        
    abstract function GetDatabase();
}
