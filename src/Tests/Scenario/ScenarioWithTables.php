<?php
/**
 * @file ScenarioWithTables.php
 * An extension to scenarios that handle the content of database tables
 * Lang en
 * Reviewstatus: 2021-09-01
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests\Scenario;

use Illuminate\Support\Facades\DB;

trait ScenarioWithTables {

    protected $references = [];
    
    protected function SetupTables() {
        $descriptors = $this->GetTableContents();
        if (empty($descriptors)) {
            return; // Perhaps no tables have to be filled
        }
        if (!is_array($descriptors)) {
            // We expect an array as a function result, if not this is an error
            throw \Exception("GetTableContents doesn't return an array");
        }
        foreach ($descriptors as $table => $descriptor) {
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
            if (is_null($value) || ($value === 'NULL') || ($value === '=>NULL')) {
                $result .= 'NULL';
            } else  if (is_string($value) && (substr($value,0,2) == '=>')) {
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
    
    abstract function GetTables();        
}
