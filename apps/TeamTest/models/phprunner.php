<?php
namespace teamtest\models;
/**
 * Class PHPTestRunner
 */

class PHPTestRunner extends TestRunner {

    function __construct() {
        $this->testRunnerCommand = '/usr/local/php/bin/phpunit'; //Absolute path to phpunit

        if (!file_exists($this->testRunnerCommand)) {
            throw new Exception('PHPUnit is not installed at ' . $this->testRunnerCommand);
        }
    }

    public function runTest($fileName) {
        $cmd = $this->testRunnerCommand . ' ' . $fileName;
        $output = `$cmd`;
        return $this->parseOutput($output);
    }

    public function parseOutput($testOutput) {
        $status = strpos($testOutput,'OK (') !== false ? $this->success() : $this->failure();
        return array(
            'status' => $status,
            'message' => $testOutput
        );
    }

}