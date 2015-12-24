<?php
namespace TimTegeler\Routerunner;

class PatternTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $this->assertEquals('/^\/(\w+)\/(\d+)$/', Pattern::build('/[string]/[numeric]'));
    }

}
