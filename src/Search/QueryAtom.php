<?php

/**
 * @file QueryAtom.php
 * Provides the base class of query atoms
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
 * The basic class for query atoms. Query atoms are build and connected by a SearchBase object and lated assembled to 
 * the query itself. 
 * @author lokal
 *
 */
abstract class QueryAtom 
{
 
    /**
     * Der SearchBase object that this QueryAtom belongs to
     * @var SearchBase
     */
    protected $parent_query;
    
    /**
     * A link to another QueryAtom of the same type (if linking is allowed)
     * @var unknown
     */
    protected $next;
    
    /**
     * A links to the previous QueryAtom of the same type 
     * @var unknown
     */
    protected $prev;
    
    /**
     * A boolean that indicated, if this QueryAtom is a singleton or if it can be connected
     * @var boolean
     */
    protected $is_singleton = false;
    
    protected $order = 0;
    
    protected $connection;
    
    /**
     * Creates a new QueryAtom and passes the parent query over
     * @param QueryBuilder $parent_query
     */
    public function __construct(SearchBase $parent_query) 
    {
        $this->parent_query = $parent_query;
    }
    
    public function setPrev(QueryAtom $prev) 
    {
        $this->prev = $prev;
    }
    
    /**
     * Links an atom to this atom or raises an exception if this is a singleton atom
     * @param $next the linked following atom
     * @param $connection the kind of connection between this two
     */
    public function link(QueryAtom $next, string $connection = 'and') 
    {
        if ($this->isSingleton()) {
            throw new QueryException("A singleton query atom can't be linked.");
        }
        if (is_null($this->next)) {
            $this->next = $next;
            $next->setPrev($this);
            $this->connection = $connection;            
        } else {
            $this->next->link($next,$connection);
        }
    }
    
    /**
     * Returns if this atom is a singleton or linkable
     * @return unknown
     */
    public function isSingleton() 
    {
        return $this->is_singleton;
    }
    
    /**
     * Return the order of this query part
     * @return number
     */
    protected function getOrder() 
    {
        return $this->order;
    }
    
}