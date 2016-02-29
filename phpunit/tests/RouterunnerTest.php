<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Mock\Encoder;
use TimTegeler\Routerunner\Mock\Login;
use TimTegeler\Routerunner\Mock\LoginFalse;
use TimTegeler\Routerunner\Mock\LoginTrue;

class RouterunnerTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->route("GET", "/[numeric]/[string]", "Index->get");
        $routerunner->route("POST", "/[numeric]/[string]", "Index->post");
        $this->assertEquals("index->get", $routerunner->execute("GET", "/123/tim"));
        $this->assertEquals("index->post", $routerunner->execute("POST", "/123/tim"));
    }

    public function testExecuteWithCache()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->setCacheFile(__DIR__ . "/../assets/cache");
        $routerunner->setCaching(true);
        $routerunner->route("GET", "/[numeric]/[string]", "Index->get");
        $routerunner->route("POST", "/[numeric]/[string]", "Index->post");
        $this->assertEquals("index->get", $routerunner->execute("GET", "/123/tim"));
        $this->assertEquals("index->post", $routerunner->execute("POST", "/123/tim"));
    }

    public function testExecuteWithBaseUri()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->setBaseUri("/test");
        $routerunner->route("GET", "/[numeric]/[string]", "Index->get");
        $routerunner->route("POST", "/[numeric]/[string]", "Index->post");
        $this->assertEquals("index->get", $routerunner->execute("GET", "/test/123/tim"));
        $this->assertEquals("index->post", $routerunner->execute("POST", "/test/123/tim"));
    }

    public function testExecuteFallback()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->parse(__DIR__ . "/../assets/routes");
        $this->assertEquals("index->get", $routerunner->execute("PUST", "/123/tim"));
    }

    public function testExecuteException()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\RouterException");
        $routerunner->execute("GET", "/");
    }

    public function testMiddlewareTrue()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->route("GET", "/[numeric]/[string]", "Index->get");
        $routerunner->route("POST", "/[numeric]/[string]", "Index->post");
        $loginMiddleware = new LoginTrue("TimTegeler\\Routerunner\\Mock\\Index","login");
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals("index->get", $routerunner->execute("GET", "/123/tim"));
        $this->assertEquals("index->post", $routerunner->execute("POST", "/123/tim"));
    }

    public function testMiddlewareLoginFalse()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->route("GET", "/[numeric]/[string]", "Index->get");
        $routerunner->route("POST", "/[numeric]/[string]", "Index->post");
        $loginMiddleware = new LoginFalse("TimTegeler\\Routerunner\\Mock\\Index","login");
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals("index->login", $routerunner->execute("GET", "/123/tim"));
        $this->assertEquals("index->login", $routerunner->execute("POST", "/123/tim"));
    }

    public function testPostprocessing()
    {
        $routerunner = new Routerunner();
        $routerunner->setControllerRootNameSpace("TimTegeler\\Routerunner\\Mock");
        $routerunner->route("GET", "/", "Index->api");
        $routerunner->setPostProcessor(new Encoder());
        $this->assertEquals('{"index":"login"}', $routerunner->execute("GET", "/"));
    }
}
