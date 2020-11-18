<?php
/**
 * @file SunhillTestCasee.php
 * Provides a common basic test for the sunhill project. It expands the laravel basic test for some 
 * helper methods (like accessing protected methods and properties)
 * Lang en
 * Reviewstatus: 2020-18-11
 * Localization: incomplete
 * Documentation: complete
 * Tests: 
 * Coverage: unknown
 */

namespace Sunhill\Basic\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class SunhillTestCase extends BaseTestCase {

    /**
     * A wrapper for receiving values from an objects
     * If $fieldname is a simple string, $loader->$fieldname is returned
     * If $fieldname is in the form something[index], $loader->something[index] is returned
     * If $fieldname is in the form something->subfield, $loader->something->subfield is returned
     * If $fieldname is in the form something[index]->subfield, $loader->something[index]->subfield is returned
     * @param unknown $loader
     * @param unknown $fieldname
     * @return unknown
     */
    protected function get_field($loader,$fieldname) {
        $match = '';
        if (preg_match('/(?P<name>\w+)\[(?P<index>\w+)\]->(?P<subfield>\w+)/',$fieldname,$match)) {
            $name = $match['name'];
            $subfield = $match['subfield'];
            $index = $match['index'];
            return $loader->$name[$index]->$subfield;
        } else if (preg_match('/(?P<name>\w+)\[(?P<index>\w+)\]\[(?P<index2>\w+)\]/',$fieldname,$match)) {
            $name = $match['name'];
            $index2 = $match['index2'];
            $index = $match['index'];
            return $loader->$name[$index][$index2];
        } else if (preg_match('/(?P<name>\w+)->(?P<subfield>\w+)/',$fieldname,$match)) {
            $name = $match['name'];
            $subfield = $match['subfield'];
            return $loader->$name->$subfield;
        } if (preg_match('/(?P<name>\w+)\[(?P<index>\w+)\]/',$fieldname,$match)){
            $name = $match['name'];
            $index = $match['index'];
            return $loader->$name[$index];
        }  else if (is_string($fieldname)){
            return $loader->$fieldname;
        } else {
            return $loader;
        }
    }
    
    /**
     * copied from https://jtreminio.com/blog/unit-testing-tutorial-part-iii-testing-protected-private-methods-coverage-reports-and-crap/
     * Calls the protected or private method "$methodName" of the object $object with the given parameters and 
     * returns its result
     * @param unknown $object
     * @param unknown $methodName
     * @param array $parameters
     * @return unknown
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
    
    /**
     * copied and modified from https://stackoverflow.com/questions/18558183/phpunit-mockbuilder-set-mock-object-internal-property
     * Sets the value of the property "$property_name" of object "$object" to value "$value"
     * @param unknown $object
     * @param unknown $property_name
     * @param unknown $value
     */
    public function setProtectedProperty(&$object,$property_name,$value) {
        $reflection = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property_name);
        $reflection_property->setAccessible(true);
        
        $reflection_property->setValue($object, $value);
    }
    
    /**
     * copied and modified from https://stackoverflow.com/questions/18558183/phpunit-mockbuilder-set-mock-object-internal-property
     * Returns the value of the property "$property_name" of object "$object"
     * @param unknown $object
     * @param unknown $property_name
     */
    public function getProtectedProperty(&$object,$property_name) {
        $reflection = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property_name);
        $reflection_property->setAccessible(true);
        
        return $reflection_property->getValue($object);
    }
    
    /**
     * The following two methods are helpers to test if one array is contained in another
     * @param unknown $expect
     * @param unknown $test
     * @return boolean
     */
    protected function checkArrays($expect,$test) {
        foreach ($expect as $key => $value) {
            if (!array_key_exists($key, $test)) {
                return false;
            }
            if (is_array($value)) {
                if (!$this->checkArrays($value,$test[$key])) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Tests recursive if all entries of $expect are contained in $test.  
     * @param unknown $expect
     * @param unknown $test
     */
    protected function assertArrayContains($expect,$test) {
        if (!$this->checkArrays($expect,$test)) {
            $this->fail("The expected array is not contained in the passed one");
            return;
        }
        $this->assertTrue(true);
    }
        
}
