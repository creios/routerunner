<?php
namespace TimTegeler\Routerunner;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {

        Router::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        Router::route("GET", "/[numeric]/[string]", "Index->get");
        Router::route("POST", "/[numeric]/[string]", "Index->post");

        $this->assertEquals("index->get", Router::execute("GET", "/123/tim"));
        $this->assertEquals("index->post", Router::execute("POST", "/123/tim"));
    }

}
