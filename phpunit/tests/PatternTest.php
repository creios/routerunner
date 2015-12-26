<?php
namespace TimTegeler\Routerunner;

class PatternTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $this->assertEquals('/^\/(\w+)\/(\d+)$/', Pattern::buildUri('/[string]/[numeric]'));
        $this->assertEquals('/^GET$/', Pattern::buildHttpMethod('GET'));
        $this->assertEquals('/^POST$/', Pattern::buildHttpMethod('POST'));
        $this->assertEquals('/^GET|POST$/', Pattern::buildHttpMethod('*'));
    }

}
