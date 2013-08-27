<?php
namespace teamtest\models;
/**
 * Class TestRunner
 *
 * A skeleton class for any TestRunner. Methods must be implemented by a real TestRunner first
 */

class TestRunner {
    protected $testRunnerCommand = ''; //The absolute path to a unit test runner command
    protected static $SUCCESS = 'SUCCESS';
    protected static $FAILURE = 'FAILURE';

    public function runTest($fileName){
        //This is usually something of the form:
        //$cmd = $this->testRunnerCommand . ' ' . $fileName;
        //$output = `$cmd`;
        //return $this->parseOutput($output);

        throw new Exception('runTest($fileName) not yet implemented!');
    }

    public function parseOutput($testOutput) {
        //The output of a parseOutput() function should contain at least the following keys
        return array(
            'status' => $this->failure(),
            'message' => 'parseOutput($testOutput) not yet implemented!'
        );
    }

    public static function success() {
        return self::$SUCCESS;
    }

    public static function failure() {
        return self::$FAILURE;
    }
}