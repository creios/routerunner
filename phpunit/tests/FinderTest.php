<?php
namespace TimTegeler\Routerunner;

class FinderTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchesRoute()
    {
        $route = new Route("*", "/", "foo->bar");
        $this->assertEquals(array(), Finder::matchesRoute($route, "GET", "/"));
        $this->assertEquals(array(), Finder::matchesRoute($route, "POST", "/"));
        $route = new Route("GET", "/", "foo->bar");
        $this->assertEmpty(Finder::matchesRoute($route, "GET", "/"));
        $route = new Route("POST", "/subpath/[numeric]/[string]", "foo->bar");
        $this->assertEquals(array("123", "tim"), Finder::matchesRoute($route, "POST", "/subpath/123/tim"));
    }

    public function testAddSetGetReset()
    {
        Finder::resetRoutes();
        Finder::addRoute(new Route("GET", "/", "index->get"));
        Finder::addRoute(new Route("POST", "/", "start->post"));

        $routes = Finder::getRoutes();
        $this->assertEquals(2, count($routes));

        $routesToSet = array(new Route("GET", "/", "index->get"));
        Finder::setRoutes($routesToSet);
        $this->assertEquals($routesToSet, Finder::getRoutes());

        Finder::resetRoutes();
        $routes = Finder::getRoutes();
        $this->assertEquals(0, count($routes));
    }

    public function testFind()
    {
        Finder::resetRoutes();
        Finder::addRoute(new Route("GET", "/", "index->get"));
        Finder::addRoute(new Route("POST", "/subpath/[numeric]/[string]", "index->post"));
        Finder::addRoute(new Route("GET", "/[string]/[numeric]/subpath", "index->get"));

        $route = Finder::findRoute("GET", "/");
        $this->assertEquals("GET", $route->getHttpMethod());
        $this->assertEquals("/", $route->getUri());
        $this->assertEquals("index->get", $route->getCallable());

        $route = Finder::findRoute("POST", "/subpath/123/tim");
        $this->assertEquals("POST", $route->getHttpMethod());
        $this->assertEquals("/subpath/[numeric]/[string]", $route->getUri());
        $this->assertEquals("index->post", $route->getCallable());

        $route = Finder::findRoute("POST", "/subpath/123.34/tim");
        $this->assertEquals("POST", $route->getHttpMethod());
        $this->assertEquals("/subpath/[numeric]/[string]", $route->getUri());
        $this->assertEquals("index->post", $route->getCallable());

        $route = Finder::findRoute("GET", "/tim/123/subpath");
        $this->assertEquals("GET", $route->getHttpMethod());
        $this->assertEquals("/[string]/[numeric]/subpath", $route->getUri());
        $this->assertEquals("index->get", $route->getCallable());
    }

    public function testFindException()
    {
        Finder::resetRoutes();
        Finder::addRoute(new Route("GET", "/", "index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\RouterException");
        Finder::findRoute("PUT", "/");
    }

}
