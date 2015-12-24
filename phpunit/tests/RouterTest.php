<?php
namespace TimTegeler\Routerunner;

class RouterunnerTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {

        Routerunner::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        Routerunner::route("GET", "/[numeric]/[string]", "Index->get");
        Routerunner::route("POST", "/[numeric]/[string]", "Index->post");

        $this->assertEquals("index->get", Routerunner::execute("GET", "/123/tim"));
        $this->assertEquals("index->post", Routerunner::execute("POST", "/123/tim"));
    }

}
