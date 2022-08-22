<?php

/**
 * @file Searchbase.php
 * Provides a basic search interface for different searches
 * Lang en
 * Reviewstatus: 2022-08-22
 * Localization: complete
 * Documentation: complete
 * Tests: Unit/SearchBaseTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Search;

abstract class SearchBase 
{
  
    /**
     * Checks if the given variable $variable is valid and return a representation of this variable or null if not valid.
     * @param $variable string: The name of the variable
     * @return any|null: If the variable is not found, it return null. If found it return a representation of the variable (e.g. a property object) or the
     * variable string itself. This representation is then processed
     */
    abstract protected function checkVariable(string $variable);
  
    /**
     * Adds a condition to the query. 
     * @param $connection string (and|or): How this condition should be added to the other conditions
     * @param $variable string: The name of the conditional variable
     * @param $relation string|null: If $condition and $relation is null, that assume "= true", if $condition is null assume "=" and make $relation to $condition
     * @param $condition any: see above
     * @return SearchBase
     */
    protected function addWhere(string $connection, string $variable, $relation=null, $condition=null): SearchBase
    {
        // Handle omitted parameters
        if (is_null($condition) && (is_null($relation))) {
          $relation = '=';
          $condition = true;
        }  else if(is_null($condition)) {
          $condition = $relation;
          $rleation = '=';
        }  
        
        // Check connection 
        $connection = strtolower($connection);
        if (($connection !== 'and') && ($connection !== 'or')) {
            throw new \Exception(__("The connection must be 'and' or 'or', ':connection' given.",array('connection'=>$connection)));
        }                                 
        
        // Check variable
        if ($variable_obj = $this->checkVariable($variable)) {
            throw new \Exception(__("The variable ':variable' is not valid.",['variable'=>$variable]));
        }  
        return $this;    
    }
  
    public function where($variable, $relation=null, $condition=null): SearchBase
    {
        return $this->addWhere('and',$variable,$relation,$condition);
    }
  
    public function orWhere($variable, $relation=null, $condition=null): SearchBase
    {
        return $this->addWhere('or',$variable,$relation,$condition);
    }
  
    /**
     * Adds an order clause
     * The result should be ordered by $variable. if $ascending is set, in an ascending order if not in an descending order
     * @param $variable: What variable should be ordered
     * @param $ascending: bool=true: In which direction should be ordered
     */
    public function orderBy(string $variable, bool $ascending = true): SearchBase
    {
    }
    
    /**
     * Sets an limit and/or an offset to the query
     * If $limit is omitted, $offset is taken as limit
     * @param $offset int: The offset of the query
     * @param $limit int|null: how many entries should be returned
     */
    public function limit(int $offset, $limit = null): SearchBase
    {        
      if (is_null($limit)) {
          $limit = $offset;
          $offset = 0;
      }  
    }
   
}  
