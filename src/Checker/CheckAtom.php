<?php

/**
 * @file CheckAtom.php
 * Provides a single check that can be executed
 * Lang en
 * Reviewstatus: 2023-03-14
 * Localization: nothing to translate
 * Documentation: complete
 * Tests: Unit/Checker/CheckAtomTest.php
 * Coverage: unknown
 * PSR-State: complete
 */

namespace Sunhill\Basic\Checker;

use Sunhill\Basic\Loggable;

abstract class CheckAtom extends Loggable
{
    
    /**
     * Number of subtests run in total in this atom
     * @var integer
     */
    protected int $subtests_run = 0;
    
    /**
     * Number of subtests that failed in this atom
     * @var integer
     */
    protected int $subtests_failed = 0;
    
    /**
     * Number of failures that where corrected
     * @var integer
     */
    protected int $failures_corrected = 0;
    
    /**
     * Boolean than indicates, if errors should be corrected (if possible), default = false
     * @var boolean
     */
    protected bool $correct = false;
    
    /**
     * The error message
     * @var string
     */
    protected $error_message = 'Test(s) failed.';
    
    /**
     * Setter for the previous var. Has no getter because only used internally
     * @param bool $value
     * @return CheckAtom
     * @test CheckAtomTest:testCorrect
     */
    public function setCorrect(bool $value = true): CheckAtom
    {
        $this->correct = $value;
        return $this;
    }
    
    /**
     * The runner itself
     * @return bool
     * @test CheckAtomTest:testSuccessfulRun
     */
    abstract protected function doRun(): bool;
    
    /**
     * Method that tells this atom to run the tests.
     * @return bool, true if there was no error otherwise false
     * @test CheckAtomTest:testSuccessfulRun
     */
    public function run(): bool
    {
        return $this->doRun();
    }
    
    /**
     * Returns th number of subtests run in total
     * @return int
     * @test CheckAtomTest:testSuccessfulRun
     */
    public function getSubtestsRun(): int
    {
        return $this->subtests_run;
    }
    
    /**
     * Returns the number of subtests than failed
     * @return int
     * @test CheckAtomTest:testSuccessfulRun
     */
    public function getSubtestsFailed(): int
    {
        return $this->subtests_failed;
    }
    
    /**
     * Should return a more verbous error message
     * @return string
     */
    public function getFailureMessage(): string
    {
        return $this->error_message;    
    }
    
    /**
     * Return the number of subtests that passed
     * @return int
     * @test CheckAtomTest:testSuccessfulRun
     */
    public function getSubtestsPassed(): int
    {
        return $this->subtests_run - $this->subtests_failed;
    }
    
    /**
     * Should be called if ALL subtests passed
     * @param int $number_of_subtests
     * @test CheckAtomTest:testSuccessfulRun
     */
    protected function testPassed(int $number_of_subtests = 1)
    {
        $this->subtests_run    = $number_of_subtests;
        $this->subtests_failed = 0;
        return true;
    }
    
    protected function testFailed(int $number_of_subtests = 1, $number_of_subtests_failed = 1, $message = "Test(s) failed.")
    {
        $this->subtests_run = $number_of_subtests;
        $this->subtests_failed = $number_of_subtests;
        $this->error_message = $message;
        return false;
    }
}