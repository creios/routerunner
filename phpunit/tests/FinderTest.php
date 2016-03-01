<?php
namespace TimTegeler\Routerunner;

class FinderTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchesRoute()
    {
        $finder = new Finder();
        $route = new Route('*', '/', new Callback('foo','bar'));
        $this->assertEquals(array(), $finder->matchesRoute($route, 'GET', '/'));
        $this->assertEquals(array(), $finder->matchesRoute($route, 'POST', '/'));
        $route = new Route('GET', '/', new Callback('foo','bar'));
        $this->assertEmpty($finder->matchesRoute($route, 'GET', '/'));
        $route = new Route('POST', '/subpath/[numeric]/[string]', new Callback('foo','bar'));
        $this->assertEquals(array('123', 'tim'), $finder->matchesRoute($route, 'POST', '/subpath/123/tim'));
    }

    public function testAddSetGetReset()
    {
        $finder = new Finder();
        $finder->addRoute(new Route('GET', '/', new Callback('index','get')));
        $finder->addRoute(new Route('POST', '/', new Callback('start','post')));
        $this->assertEquals(2, count($finder->getRoutes()));

        $routesToSet = array(new Route('GET', '/', new Callback('index','get')));
        $finder->setRoutes($routesToSet);
        $this->assertEquals($routesToSet, $finder->getRoutes());

        $finder->addRoutes(array(new Route('POST', '/', new Callback('start','post'))));
        $this->assertEquals(2, count($finder->getRoutes()));
    }

    public function testFind()
    {
        $finder = new Finder();
        $finder->addRoute(new Route('GET', '/', new Callback('index','get')));
        $finder->addRoute(new Route('POST', '/subpath/[numeric]/[string]', new Callback('index','post')));
        $finder->addRoute(new Route('GET', '/[string]/[numeric]/subpath', new Callback('index','get')));

        $route = $finder->findRoute('GET', '/');
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/', $route->getUri());
        $this->assertEquals('index', $route->getCallback()->getController());
        $this->assertEquals('get', $route->getCallback()->getMethod());

        $route = $finder->findRoute('POST', '/subpath/123/tim');
        $this->assertEquals('POST', $route->getHttpMethod());
        $this->assertEquals('/subpath/[numeric]/[string]', $route->getUri());
        $this->assertEquals('index', $route->getCallback()->getController());
        $this->assertEquals('post', $route->getCallback()->getMethod());

        $route = $finder->findRoute('POST', '/subpath/123.34/tim');
        $this->assertEquals('POST', $route->getHttpMethod());
        $this->assertEquals('/subpath/[numeric]/[string]', $route->getUri());
        $this->assertEquals('index', $route->getCallback()->getController());
        $this->assertEquals('post', $route->getCallback()->getMethod());

        $route = $finder->findRoute('GET', '/tim/123/subpath');
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/[string]/[numeric]/subpath', $route->getUri());
        $this->assertEquals('index', $route->getCallback()->getController());
        $this->assertEquals('get', $route->getCallback()->getMethod());;
    }

    public function testFindException()
    {
        $finder = new Finder();
        $finder->addRoute(new Route('GET', '/', new Callback('index','get')));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException');
        $finder->findRoute('PUT', '/');
    }

}
