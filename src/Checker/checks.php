<?php
/**
 * @file checks.php
 * Provides a class that performs checks
 * Lang en
 * Reviewstatus: 2020-12-20
 * Localization: incomplete
 * Documentation: complete
 * Tests: BasicTest.php
 * Coverage: unknown
 */
namespace Sunhill\Basic\Checker;

use Sunhill\Basic\loggable;
use Sunhill\Basic\Checker\CheckException;
use Sunhill\Basic\Checker\checker;

class checks extends loggable {
    
    protected $checker_classes = [];
    
    public function InstallChecker(string $class_name) {
        $this->checker_classes[] = $class_name;    
    }
    
    public function Check() {
        if (empty($this->checker_classes)) {
            throw new CheckException('No checkers installed');
        }
        return $this->walk_checkers();
    }
    
    protected function walk_checkers() {
        $result = [];
        foreach ($this->checker_classes as $checker_class) {
            $checker = new $checker_class();
            $result = array_merge($result,$this->perform_checks($checker));
        }
        return $result;
    }
    
    protected function perform_checks(checker $checker) {
        $result = [];
        $methods = get_class_methods($checker);
        foreach ($methods as $method) {
            if (substr($method,0,5) == 'check') {
                $result[] = $checker->$method();
            }
        }
        return $result;
    }
}