<?php

require_once __DIR__ . '/../../../models/runner.php';

class runnerTest extends PHPUnit_Framework_TestCase {
    var $runner;
    function setUp(){
        $this->runner = new \teamtest\models\runner;
    }

    function testIsRunnerObject(){
        $this->assertTrue(is_object($this->runner));
    }

    function testGetRunners(){
        /* should be 4 currently */
        $runners = $this->runner->getRunners(__DIR__ . '/../../../runners/');
        $this->assertEquals(4, count($runners), 'Currently 4 test runners are in place');
    }

    function testValidRunner(){
        $bad_runner = array();
        $bad_runner['test']['pass']= 'passme';
        $this->assertFalse($this->runner->validRunner($bad_runner), 'cmd not set in tests');

        $good_runner = array();
        $good_runner['test']['pass'] = 'passme';
        $good_runner['test']['cmd'] = 'some cmd here';
        $good_runner['filetypes'] = array('php','js','py');
        $this->assertTrue($this->runner->validRunner($good_runner), 'A valid runner');
    }

    function testGetRunner(){
        $php_runner = $this->runner->getRunner(pathinfo('/tmp/something.php'));
        $this->assertEquals('php', $php_runner['filetypes'][0], 'PHP Test runner should have been returned');

        $python_runner = $this->runner->getRunner(pathinfo('/tmp/something.py'));
        $this->assertEquals('py', $python_runner['filetypes'][0], 'Python Test runner should have been returned');


        $java_runner = $this->runner->getRunner(pathinfo('/tmp/something.java'));
        $this->assertEquals('java', $java_runner['filetypes'][0], 'Java Test runner should have been returned');

        $javascript_runner = $this->runner->getRunner(pathinfo('/tmp/something.html'));
        $this->assertEquals('js', $javascript_runner['filetypes'][0], 'JavaScript Test runner should have been returned');
        $this->assertEquals('html', $javascript_runner['filetypes'][1], 'JavaScript Test runner should have been returned');
    }
}