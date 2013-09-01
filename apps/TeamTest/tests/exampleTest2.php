<?php
class wooter extends PHPUnit_Framework_TestCase
{
    public function testWoot()
    {
        $this->assertTrue(true);
    }

    public function testBleh(){
        $this->assertFalse(true);
    }
}