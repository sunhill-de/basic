<?php
/**
 * @file checker.php
 * Provides a class for checks that are called by the Check facade
 * Lang en
 * Reviewstatus: 2020-12-20
 * Localization: nothing to translate
 * Documentation: complete
 * Tests: BasicTest.php
 * Coverage: unknown
 * PSR-State: complete
 */

namespace Sunhill\Basic\Checker;

use Sunhill\Basic\Loggable;
use Sunhill\Basic\Utils\Descriptor;

class Checker extends Loggable {
    
    /**
     * Creates a checker result and passes it to an descriptor
     * @param string $status
     * @param string $test_name
     * @param string $error_message
     * @return descriptor
     */
    protected function createResult(string $status, string $test_name, string $error_message = ''): descriptor 
    {
        $result = new descriptor();
        $result->result = $status;
        $result->name = $test_name;
        $result->error = $error_message;
        return $result;
        
    }
}
