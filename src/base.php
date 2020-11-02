<?php
/**
 * @file base.php
 * Provides a common basic class for all sunhill project classes
 * Lang en
 * Reviewstatus: 2020-10-07
 * Localization: incomplete
 * Documentation: complete
 * Tests: BasicTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic;


/**
 * Basic common class for all classes of the sunhill project 
 * @author klaus
 *
 */
class base {
	
    /**
     * Empty constructur so parent::__construct() always works
     */
    public function __construct() {        
    }
    
    /**
     * Catchall for unknown variables. It tries to find a get_$varname method and calls it if found. Otherwiese it throws 
     * an excpetion. 
     * @param string $varname Name of the variable
     * @throws SunhillException is throws if no getter is found
     * @return any The value of the variable (return of the getter)
     */
    public function __get(string $varname) {
		$method = "get_$varname";
		if (method_exists($this,$method)) {
			return $this->$method();
		} else {
			throw new SunhillException("Variable '$varname' was not found.");
		}
	}
	
    /**
     * Set-Catchall for unknown variables. It tries to find a set_$varname method and calls it if found. 
     * @param string $varname Name of the variable
     * @param unknown $value Value of the variable
     * @throws SunhillException Is thrown if there is no setter
     * @return unknown
     */
	public function __set($varname,$value) {
		$method = "set_$varname";
		if (method_exists($this,$method)) {
			return $this->$method($value);
		} else {
			throw new SunhillException("Variable '$varname' was not found.");
		}
	}
	
}