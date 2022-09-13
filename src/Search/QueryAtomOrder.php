<?php

/**
 * @file QueryAtomOrder.php
 * Provides the query atom for ordering
 * Lang en
 * Reviewstatus: 2022-08-23
 * Localization: none
 * Documentation: incomplete
 * Tests: 
 * Coverage: unknown
 * Dependencies: none
 * PSR-State: incompleted
 */

namespace Sunhill\Basic\Search;

/**
 * This class collects the information about the ordering of the result 
 * the query itself. 
 * @author lokal
 *
 */
class QueryAtomOrder extends QueryAtom
{
 
    /**
     * Stores the variable that should be used as key
     * @var unknown
     */
    protected $variable;
    
    /**
     * Stores the direction of ordering
     * @var unknown
     */
    protected $ascending;
    
    /**
     * Setter for $variable
     * @param unknown $variable
     * @return \Sunhill\Basic\Search\QueryAtomOrder
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;    
        return $this;
    }
    
    /**
     * Getter for $variable
     * @return unknown
     */
    public function getVariable() 
    {
        return $this->variable;
    }
    
    /**
     * Setter for $ascending
     * @param unknown $variable
     * @return \Sunhill\Basic\Search\QueryAtomOrder
     */
    public function setAscending(bool $ascending)
    {
        $this->ascending = $ascending;
        return $this;
    }
    
    /**
     * Getter for $ascending
     * @return unknown
     */
    public function getAscending()
    {
        return $this->ascending;
    }
       
}