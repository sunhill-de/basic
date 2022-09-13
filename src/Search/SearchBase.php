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
     * Refers to the class that initiated the search
     * @var unknown
     */
    protected $calling_class;
    
    /**
     * Stores the single parts of the query that are added through the public statements
     * @var array
     */
    protected $query_parts = [];
    
    /**
     * Creates a new query object and passes the calling object class over
     */
    public function __construct(string $classname = '')
    {
        if (!empty($classname)) {
            $this->setCallingClass($classname);
        }
    }
    
    /**
     * Since a search is initiazied by a specific class, the class is set here
     * @param unknown $calling_class
     * @return \Sunhill\Base\Search\SearchBase
     */
    public function setCallingClass($calling_class)
    {
        $this->calling_class = $calling_class;
        return $this;
    }
    
    /**
     * Returns the calling class
     * @return unknown
     */
    public function getCallingClass()
    {
        return $this->calling_class;
    }
    
    /**
     * Returns the part of the query identified by $part_id
     * @param string $part_id
     * @return string|unknown
     */
    protected function getQueryPart(string $part_id)
    {
        return isset($this->query_parts[$part_id])?$this->query_parts[$part_id]:null;
    }
    
    /**
     * Sets a new query part identified by $part_id
     * @param string $part_id
     * @param QueryAtom $part
     */
    protected function setQueryPart(string $part_id, QueryAtom $part, $connection = null)
    {
        if (!isset($this->query_parts[$part_id])) {
            // This part is not set yet, so just set it
            $this->query_parts[$part_id] = $part;
        } else {
            // This part is already set, so decide what to do
            if ($part->isSingleton()) {
                // replace a singleton
                $this->query_parts[$part_id] = $part;
            } else {
                $this->query_parts[$part_id]->link($part,$connection);
            }
        }
    }
    
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
  
    /**
     * Adds an "and" connected where statement to the atom list
     * @param unknown $variable
     * @param unknown $relation
     * @param unknown $condition
     * @return SearchBase
     */
    public function where($variable, $relation=null, $condition=null): SearchBase
    {
        return $this->addWhere('and',$variable,$relation,$condition);
    }
  
    /**
     * Adds an "or" connected where statement to the atom list
     * @param unknown $variable
     * @param unknown $relation
     * @param unknown $condition
     * @return SearchBase
     */
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
        // Check variable
        if ($variable_obj = $this->checkVariable($variable)) {
            throw new \Exception(__("The variable ':variable' is not valid.",['variable'=>$variable]));
        }
        
        $atom = new QueryAtomOrder($this);
        $atom->setVariable($varible)->setAscending($ascending);
        $this->setQueryPart('order',$atom);
        
        return $this;
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
      return $this;
    }
   
}  
