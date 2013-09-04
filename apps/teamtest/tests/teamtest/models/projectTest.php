<?php

require_once __DIR__ . '/../../../models/project.php';

class projectTest extends PHPUnit_Framework_TestCase {
    var $projects;
    var $current_projects;
    function setUp(){
        $this->projects = new \teamtest\models\project;
        $this->current_projects = count($this->projects->getAll());
    }

    function testIsObject(){
        $this->assertTrue(is_object($this->projects), 'At this point this->projects should be an object');
    }

    function testCreateRemoveProject(){
        $this->projects->create('kcwazheretest', '/home/cmerrill/something/');
        $this->assertEquals(count($this->projects->getAll()), ($this->current_projects + 1), 'We just created a project, so there should be curent_projects + 1');
        $this->projects->remove('kcwazheretest');
        $this->assertEquals(count($this->projects->getAll()), $this->current_projects, 'After removing a project, we should be back to what we were previously');


    }
}