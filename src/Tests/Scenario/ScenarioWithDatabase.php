<?php
/**
 * @file ScenarioWithDatabase.php
 * An extension to scenarios that handle handle databases
 * Lang en
 * Reviewstatus: 2021-08-05
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

use Illuminate\Support\Facades\DB;

trait ScenarioWithDatabase {

    protected $references = [];
    
    /**
     * Creates all the tables that are defined in the GetTableDescriptors function
     */
    protected function SetUpDatabase() {
        $descriptors = $this->GetTableDescriptors();
        if (empty($descriptors)) {
            return; // Perhaps no new tables have to be created
        }
        if (!is_array($descriptors)) {
            // We expect an array as a function result, if not this is an error
            throw \Exception("GetTableDescriptors doesn't return an array");
        }
        foreach ($descriptors as $table => $descriptor) {
            $this->setupTable($table,$descriptor);
        }
    }
    
    protected function setupTable(string $tablename,array $descriptor) {
        // Descriptor should be an array of string with the column definition of the table
        $query_str = $this->GetQueryStr($tablename,$descriptor);
        DB::statement($query_str);
    }
    
    /**
     * Create the mysql query string that creates the given table
     * @param string $tablename
     * @param array $descriptor
     * @return string
     */
    protected function getQueryStr(string $tablename,array $descriptor) {
        $query_str = "create table $tablename (";
        $first = true;
        foreach ($descriptor as $line) {
            $query_str .= ($first?'':',').$line;
            $first = false;
        }
        return $query_str.');';
    }
    
    protected function SetUpTables() {
        $descriptors = $this->GetTableContents();
        if (empty($descriptors)) {
            return; // Perhaps no tables have to be filled
        }
        if (!is_array($descriptors)) {
            // We expect an array as a function result, if not this is an error
            throw \Exception("GetTableContents doesn't return an array");
        }
        foreach ($descriptor as $table => $descriptor) {
            $this->fillTable($table,$descriptor);
        }        
    }
    
    protected function fillTable(string $tablename,array $descriptor) {
        $fields = $descriptor[0];
        $values = $descriptor[1];
        foreach ($values as $reference => $realvalues) {
            $this->insertSingleValue($tablename,$fields,$reference,$realvalues);
        }
    }
    
    protected function insertSingleValue($tablename,$fields,$reference,$values) {
        $query_str = $this->getInsertQueryStr($tablename,$fields,$values);
        DB::statement($query_str);
        
        if (is_string($reference)) {
            $id = DB::getPdo()->lastInsertId();
            $this->references[$reference] = $id;
        }        
    }
    
    protected function getInsertQueryStr($tablename,$fields,$values) {
        $result = "insert into $tablename (";
        $first = true;
        foreach ($fields as $field) {
            $result .= ($first?'':',').$field;
            $first = false;
        }
        $result .= ') values (';
        $first = true;
        foreach ($values as $value) {
            $result .= $first?'':',';
            if (is_string($value) && (substr($value,0,2) == '=>')) {
                $result .= "'".$this->getReference($value)."'";
            } else {
                $result .= "'".$value."'";
            }
            $first = false;
        }
        return $result.')';
    }
    
    protected function getReference($reference) {
        $reference = substr($reference,2);
        if (strpos($reference,'->')) {
            $reference = substr($reference,0,strpos($reference,'->'));       
        }
        if (isset($this->references[$reference])) {
            return $this->references[$reference];
        } else {
            throw new \Exception("Unknown reference '$reference'");
        }
    }
    
    abstract function GetTableDescriptors();
    abstract function GetTableContents();        
}
