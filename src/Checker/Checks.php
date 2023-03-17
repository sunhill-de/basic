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
    
    /**
     * Stores the installed checker classes
     * @var array
     */
    protected $checker_classes = [];
   
    /**
     * Stores the number of tests totally performed by this instance
     * @var integer
     */
    protected int $tests_performed = 0;
    
    protected int $tests_passed = 0;
    
    protected int $tests_failed = 0;
    
    protected int $tests_repaired = 0;
    
    protected int $tests_unrepairable = 0;
    
    protected array $messages = [];
    
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
    public function check(bool $repair=false, $callback = null): array 
    {
        if (empty($this->checker_classes)) {
            throw new CheckException(__("No checkers installed"));
        }
        $this->initializeChecks();
        $this->walkCheckers($repair, $callback);
        return $this->messages;
    }

    /**
     * Returns the total number of tests that where performed
     * @return int
     */
    public function getTestsPerformed(): int
    {
        return $this->tests_performed;    
    }

    /**
     * Returns the total number of tests that passed
     * @return int
     */
    public function getTestsPassed(): int
    {
        return $this->tests_passed;    
    }
    
    /**
     * Returns the total number of tests that failed. This number is increased every time
     * when a test failed without bothering if the failure was repaired or not
     * @return int
     */
    public function getTestsFailed(): int
    {
        return $this->tests_failed;    
    }
    
    public function getTestsRepaired(): int
    {
        return $this->tests_repaired;        
    }
    
    public function getTestsUnrepairable(): int
    {
        return $this->tests_unrepairable;    
    }
    
    public function getMessages(): array
    {
        return $this->messages;    
    }
    
    /**
     * Resets all parameters
     */
    protected function initializeChecks()
    {
        $this->tests_performed = 0;
        $this->tests_passed = 0;
        $this->tests_failed = 0;
        $this->tests_repaired = 0;
        $this->tests_unrepairable = 0;
        $this->messages = [];        
    }
        
    /**
     Runs through each installed checker class and calls perfOrmChecks()
     */
    protected function walkCheckers(bool $repair, $callback): array 
    {
        $result = [];
        foreach ($this->checker_classes as $checker_class) {
            $checker = new $checker_class();
            $result = array_merge($result,$this->performChecks($checker,$repair, $callback));
        }
        return $result;
    }
    
    /**
     Runs through each method that starts with check and calls it
     */
    protected function performChecks(checker $checker, bool $repair, $callback): array 
    {
        $result = [];
        $methods = get_class_methods($checker);
        foreach ($methods as $method) {
            if (substr($method,0,5) == 'check') {
                $this->performSingleCheck($checker, $method, $repair, $callback);
            }
        }
        return $result;
    }
        
    protected function performSingleCheck(checker $checker, string $method, bool $repair, $callback)
    {
        $this->doPerformSingleCheck($checker, $method, $repair);
        $this->callCallback($checker, $callback);
        $this->processSingleCheckResult($checker, $callback);
    }
    
    protected function doPerformSingleCheck(checker $checker, string $method, bool $repair)
    {
        try {
            $result[] = $checker->$method($repair);
        } catch (CheckException $e) {
            // Ignore Error
        }        
    }
    
    protected function callCallback(checker $checker, $callback)
    {
        if (is_callable($callback)) {
            $callback($checker->getLastResult());
        }        
    }
    
    protected function processSingleCheckResult(checker $checker, $callback)
    {
        switch ($checker->getLastResult()) {
            case 'passed':
                $this->lastCheckPassed();
                break;
            case 'failed':
                $this->lastCheckFailed($checker->getLastMessage());
                break;
            case 'repaired':
                $this->lastCheckRepaired($checker->getLastMessage());
                break;
            case 'unrepairable':
                $this->lastCheckUnrepairable($checker->getLastMessage());
                break;
            default:
                throw new CheckException("Unknown testresult: '".$checker->getLastResult()."'");
        }        
    }
    
    protected function lastCheckPassed()
    {
        $this->tests_performed++;
        $this->tests_passed++;
    }
    
    protected function lastCheckFailed(string $message)
    {
        $this->tests_performed++;
        $this->tests_failed++;
        $this->messages[] = $message;
    }

    protected function lastCheckRepaired(string $message)
    {
        $this->tests_performed++;
        $this->tests_failed++;
        $this->tests_repaired++;
        $this->messages[] = $message;
    }
    
    protected function lastCheckUnrepairable(string $message)
    {
        $this->tests_performed++;
        $this->tests_failed++;
        $this->tests_unrepairable++;
        $this->messages[] = $message;
    }
    
}
