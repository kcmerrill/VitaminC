<?php

require_once __DIR__ . '/../../../models/project.php';

class projectTest extends PHPUnit_Framework_TestCase {
    var $projects;
    var $current_projects;
    function setUp(){
        $this->projects = new \blackbox\models\project;
        $this->current_projects = count($this->projects->getAll());
    }

    function tearDown(){
        $this->projects->remove('kcwazheretest');
    }

    function testIsObject(){
        $this->assertTrue(is_object($this->projects), 'At this point this->projects should be an object');
    }

    function testCreateProjectSaveProjectConfig(){
        $this->projects->create('kcwazheretest', '/home/cmerrill/something/');
        $this->assertEquals(count($this->projects->getAll()), ($this->current_projects + 1), 'We just created a project, so there should be current_projects + 1');

        /* do some quick assertions to make sure the creation is succesful */
        $this->assertEquals('/home/cmerrill/something/', $this->projects->config('basepath', 'kcwazheretest'));
        $this->assertEquals('kcwazheretest', $this->projects->config('name', 'kcwazheretest'));
        $this->assertEquals(array('.git','._','.idea','.class'), $this->projects->config('ignored_files', 'kcwazheretest'));

        /* go ahead and remove the test now */
        $this->projects->remove('kcwazheretest');
        $this->assertEquals(count($this->projects->getAll()), $this->current_projects, 'After removing a project, we should be back to what we were previously');
    }

    function testRemoveProject(){
        $this->projects->create('kcwazheretest', '/home/cmerrill/something/');
        $this->assertEquals(count($this->projects->getAll()), ($this->current_projects + 1), 'We just created a project, so there should be current_projects + 1');
        $this->projects->remove('kcwazheretest');
        $this->assertEquals(count($this->projects->getAll()), $this->current_projects, 'After removing a project, we should be back to what we were previously');
    }

    function testFilename(){
        $this->assertEquals('haha--i-am-here', $this->projects->filename('haha, I am here'));
    }

    function testAddRemoveTest(){
        $this->projects->create('kcwazheretest', '/home/cmerrill/something/');
        $this->assertEquals(count($this->projects->getAll()), ($this->current_projects + 1), 'We just created a project, so there should be current_projects + 1');

        /* lets add some tests, then remove them */
        $this->assertTrue($this->projects->addTest('/tmp/somethingawesome.php', 'kcwazheretest'));
        $this->assertEquals(1, count($this->projects->projects['kcwazheretest']['tests']));
        $this->assertTrue($this->projects->removeTest($this->projects->projects['kcwazheretest']['tests'][0]['_id'], 'kcwazheretest'));
        $this->assertEquals(0, count($this->projects->projects['kcwazheretest']['tests']));

        /* goodbye! */
        $this->projects->remove('kcwazheretest');
        $this->assertEquals(count($this->projects->getAll()), $this->current_projects, 'After removing a project, we should be back to what we were previously');
    }
}
