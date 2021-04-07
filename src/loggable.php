<?php
/**
 * @file loggabe.php
 * Provides a class that provides an abstraction of logging methods
 * Lang en
 * Reviewstatus: 2020-11-02
 * Localization: complete
 * Documentation: complete
 * Tests: LoggableTest.php
 * Coverage: unknown
 */

namespace Sunhill\Basic;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

/**
 * Consts for the different log levels
 * @var unknown
 */
define('LL_DEBUG',-1);
define('LL_INFO',-2);
define('LL_NOTICE',-3);
define('LL_WARNING',-4);
define('LL_ERROR',-5);
define('LL_CRTITICAL',-6);
define('LL_ALERT',-7);
define('LL_EMERGENCY',-8);

/**
 * Baseclass for all classes that can send messages to the framework log
 * This class does a filtering via a variable called loglevel which tells the class up to what urgency the 
 * messages should be ignored.
 * The different urgencies are standarized:
 * - debug()
 * - info()
 * - notice()
 * - warning()
 * - error()
 * - critical()
 * - alert()
 * - emergency()
 * 
 * These messages receive a string of the log message
 * @author klaus
 */
class loggable extends base {
	/**
	 * Saves the command object for displaying things on the screen 
	 */
    private $command;
    
    /**
     * Saves the current log level
     * @var int
     */
    private $loglevel=LL_ERROR;
    
    /**
     * Saves the current display level
     * @var int
     */
    private $displaylevel = LL_ERROR;
    
    /**
     * Setter for the Loglevel
     * @param int $loglevel
     * @return loggable
     */
    public function set_loglevel(int $loglevel) {
        $this->loglevel = $loglevel;
        return $this;
    }
    
    /**
     * Getter for the Loglevel
     * @return int
     */
    public function get_loglevel() {
        return $this->loglevel;
    }
    
    public function set_command(Command $command) {
        $this->command = $command;
    }
    
    /**
     * Setter for the display level
     * @param int $displaylevel
     * @return loggable
     */
    public function set_displaylevel(int $displaylevel) {
        $this->displaylevel = $displaylevel;
        return $this;
    }
    
    /**
     * Getter for the Displaylevel
     * @return int
     */
    public function get_displaylevel() {
        return $this->displaylevel;
    }
    
    private function process_message(int $level,string $message) {
        if ($this->check_loglevel($level)) {
            switch ($level) {
                case LL_DEBUG:
                    Log::debug($message); break;
                case LL_INFO:
                    Log::info($message); break;
                case LL_NOTICE:
                    Log::notice($message); break;
                case LL_WARNING:
                    Log::warning($message); break;
                case LL_ERROR:
                    Log::error($message); break;
                case LL_CRTITICAL:
                    Log::critical($message); break;
                case LL_ALERT:
                    Log::alert($message); break;
                case LL_EMERGENCY:
                    Log::emergency($message); break;
            }
        }
        if ($this->check_displaylevel($level) && !is_null($this->command)) {
            if ($level > LL_WARNING) {
                $this->command->info($message);
            } else {
                $this->command->error($message);
            }
        }             
    }
    
    /**
     * Enters a debug message into the log if the loglevel is on LL_DEBUG
     * @param string $message
     */
    protected function debug(string $message) {
        $this->process_message(LL_DEBUG,$message);
    }
    
    /**
     * Enters a info message into the log if the loglevel is on LL_INFO or lower
     * @param string $message
     */
    protected function info(string $message) {
        $this->process_message(LL_INFO,$message);
    }
    
    /**
     * Enters a notice message into the log if the loglevel is on LL_NOTICE or lower
     * @param string $message
     */
    protected function notice(string $message) {
        $this->process_message(LL_NOTICE,$message);
    }
    
    /**
     * Enters a warning message into the log if the loglevel is on LL_WARNING or lower
     * @param string $message
     */
    protected function warning(string $message) {
        $this->process_message(LL_WARNING,$message);
    }
    
    /**
     * Enters an error message into the log if the loglevel is on LL_ERROR or lower
     * @param string $message
     */
    protected function error(string $message) {
        $this->process_message(LL_ERROR,$message);
    }
    
    /**
     * Enters a critical message into the log if the loglevel is on LL_CRITICAL or lower
     * @param string $message
     */
    protected function critical(string $message) {
        $this->process_message(LL_CRITICAL,$message);
    }
    
    /**
     * Enters a alert message into the log if the loglevel is on LL_ALERT or lower
     * @param string $message
     */
    protected function alert(string $message) {
        $this->process_message(LL_ALERT,$message);
    }
    
    /**
     * Enters an emergency message into the log if the loglevel is on LL_EMERGENCY or lower
     * @param string $message
     */
    protected function emergency(string $message) {
        $this->process_message(LL_EMERGENCY,$message);
    }
    
    /**
     * Checks if the requested loglevel is higher than the currently set. If it returns true, the message
     * is passed to the log system. 
     * @param int $requested
     * @return boolean
     */
    private function check_loglevel(int $requested) {
        if ($requested >= $this->loglevel) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Checks if the requested displaylevel is higher than the currently set. If it returns true, the message
     * is passed to the display
     * @param int $requested
     * @return boolean
     */
    private function check_displaylevel(int $requested) {
        if ($requested >= $this->displaylevel) {
            return true;
        } else {
            return false;
        }
    }
}