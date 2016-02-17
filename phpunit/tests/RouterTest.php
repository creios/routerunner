<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Mock\Login;
use TimTegeler\Routerunner\Mock\LoginFalse;
use TimTegeler\Routerunner\Mock\LoginTrue;

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

    public function testExecuteFallback()
    {
        Router::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        Router::parse(__DIR__ . "/../assets/routes");
        $this->assertEquals("index->get", Router::execute("PUST", "/123/tim"));
    }

    public function testExecuteException()
    {
        Finder::resetRoutes();
        Router::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\RouterException");
        Router::execute("GET", "/");
    }

    public function testGuardTrue()
    {
        Router::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        Router::route("GET", "/[numeric]/[string]", "Index->get");
        Router::route("POST", "/[numeric]/[string]", "Index->post");
        $loginGuard = new LoginTrue();
        $loginGuard->setCallable("index->login");
        Router::registerGuard($loginGuard);
        $this->assertEquals("index->get", Router::execute("GET", "/123/tim"));
        $this->assertEquals("index->post", Router::execute("POST", "/123/tim"));
    }

    public function testGuardLoginFalse()
    {
        Router::setCallableNameSpace("TimTegeler\\Routerunner\\Mock");
        Router::route("GET", "/[numeric]/[string]", "Index->get");
        Router::route("POST", "/[numeric]/[string]", "Index->post");
        $loginGuard = new LoginFalse();
        $loginGuard->setCallable("index->login");
        Router::registerGuard($loginGuard);
        $this->assertEquals("index->login", Router::execute("GET", "/123/tim"));
        $this->assertEquals("index->login", Router::execute("POST", "/123/tim"));
    }
}
