<?php
/**
 * @file checker.php
 * Provides a class for checks that are called by the Check facade
 * Lang en
 * Reviewstatus: 2020-12-20
 * Localization: incomplete
 * Documentation: complete
 * Tests: BasicTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic\Checker;

use Sunhill\Basic\loggable;
use Sunhill\Basic\Utils\descriptor;

class checker extends loggable {
    
    protected function create_result(string $status,string $test_name,string $error_message='') {
        $result = new descriptor();
        $result->result = $status;
        $result->name = $test_name;
        $result->error = $error_message;
        return $result;
        
    }
}