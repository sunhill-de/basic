<?php
/**
 * @file Checks.php
 * Provides a class that performs checks
 * Lang en
 * Reviewstatus: 2020-12-20
 * Localization: incomplete
 * Documentation: complete
 * Tests: BasicTest.php
 * Coverage: unknown
 */
namespace Sunhill\Basic\Checker;

use Sunhill\Basic\Loggable;
use Sunhill\Basic\Checker\CheckException;
use Sunhill\Basic\Checker\checker;

/**
 The class for the check performer. This class is called via the Checks facade which is normally called via an command line. 
 The checks itself are performed by a checker class. This checker class has to be installed via the InstallChecker method 
 first. The checks are performed by calling the Checks method 
 */
class Checks extends Loggable 
{
    
    protected $checker_classes = [];
   
    /**
     * This method cleans all checks so that after it there is no check installed
     */
    public function purge(): void 
    {
        $this->checker_classes = [];
    }
    
    /**
     * Every package of the sunhill framework can install one ore more checker classes. Normally this is done in the Service Routine of laravel
     * @param $class_name The fully qualified class name of the checker class.
     */
    public function installChecker(string $class_name): void 
    {
        $this->checker_classes[] = $class_name;    
    }
    
    /**
     * Runs all checks in all installed checker classes 
     * @throws CheckException if check() is called with no installed checker_class
     * @returns array of string The check resuls in an array.
     */
    public function check(bool $repair=false): array 
    {
        if (empty($this->checker_classes)) {
            throw new CheckException(__("No checkers installed"));
        }
        return $this->walkCheckers($repair);
    }
    
    /**
     Runs through each installed checker class and calls perfOrmChecks()
     */
    protected function walkCheckers(bool $repair): array 
    {
        $result = [];
        foreach ($this->checker_classes as $checker_class) {
            $checker = new $checker_class();
            $result = array_merge($result,$this->performChecks($checker,$repair));
        }
        return $result;
    }
    
    /**
     Runs through each method that starts with check and calls it
     */
    protected function performChecks(checker $checker, bool $repair): array 
    {
        $result = [];
        $methods = get_class_methods($checker);
        foreach ($methods as $method) {
            if (substr($method,0,5) == 'check') {
                $result[] = $checker->$method($repair);
            }
        }
        return $result;
    }
}
