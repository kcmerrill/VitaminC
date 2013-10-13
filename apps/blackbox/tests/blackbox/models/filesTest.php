<?php

require_once __DIR__ . '/../../../models/files.php';

class filesTest extends PHPUnit_Framework_TestCase {
    var $files;
    function setUp(){
        $this->files = new \blackbox\models\files;
    }

    function testIsObject(){
        $this->assertTrue(is_object($this->files));
    }

    function testQueryBasic(){
        $files = $this->files->query(__DIR__ . '/../../demos/', '', function($fileinfo, $query){ return true; });
        $this->assertEquals(5, count($files), '5 results should have been returned');

    }

    function testQueryWithParams(){
        $files = $this->files->query(__DIR__ . '/../../demos/', 'python', function($fileinfo, $query){
            return stristr($fileinfo->getPathname(), $query);

        });
        $this->assertEquals(1, count($files), '1 Python demo test should be found');
        $pathinfo = pathinfo($files[0]);
        $this->assertEquals($pathinfo['basename'], 'python.py');
    }
}