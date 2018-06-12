<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Components\Call;
use TimTegeler\Routerunner\Components\Request;
use TimTegeler\Routerunner\Components\Route;
use TimTegeler\Routerunner\Components\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $this->assertEquals('/^\/(\w+)\/(\d+|\d+\.\d+)$/', Router::buildUri('/(string)/(numeric)'));
        $this->assertEquals('/^GET$/', Router::buildHttpMethod('GET'));
        $this->assertEquals('/^POST$/', Router::buildHttpMethod('POST'));
        $this->assertEquals('/^PUT$/', Router::buildHttpMethod('PUT'));
        $this->assertEquals('/^DELETE$/', Router::buildHttpMethod('DELETE'));
        $this->assertEquals('/^GET|POST|PUT|DELETE$/', Router::buildHttpMethod('*'));
    }

    public function testMatchesRoute()
    {
        $finder = new Router();

        $route = new Route('*', '/', new Call('foo', 'bar'));
        $this->assertEquals(array(), $finder->matchesRoute($route, new Request('GET', '/')));
        $this->assertEquals(array(), $finder->matchesRoute($route, new Request('POST', '/')));

        $route = new Route('GET', '/', new Call('foo', 'bar'));
        $this->assertEmpty($finder->matchesRoute($route, new Request('GET', '/')));

        $route = new Route('POST', '/subpath/(numeric)/(string)', new Call('foo', 'bar'));

        $matchesRoute = $finder->matchesRoute($route, new Request('POST', '/subpath/123/tim'));
        $this->assertEquals(array(123, 'tim'), $matchesRoute);
        $this->assertTrue(is_int($matchesRoute[0]));
        $this->assertTrue(is_string($matchesRoute[1]));

        $matchesRoute = $finder->matchesRoute($route, new Request('POST', '/subpath/123.4/tim'));
        $this->assertEquals(array(123.4, 'tim'), $matchesRoute);
        $this->assertTrue(is_float($matchesRoute[0]));
        $this->assertTrue(is_string($matchesRoute[1]));
    }

    public function testAddSetGetReset()
    {
        $finder = new Router();
        $finder->addRoute(new Route('GET', '/', new Call('index', 'get')));
        $finder->addRoute(new Route('POST', '/', new Call('start', 'post')));
        $this->assertEquals(2, count($finder->getRoutes()));

        $routesToSet = array(new Route('GET', '/', new Call('index', 'get')));
        $finder->setRoutes($routesToSet);
        $this->assertEquals($routesToSet, $finder->getRoutes());

        $finder->addRoutes(array(new Route('POST', '/', new Call('start', 'post'))));
        $this->assertEquals(2, count($finder->getRoutes()));
    }

    public function testFind()
    {
        $finder = new Router();
        $finder->addRoute(new Route('GET', '/', new Call('index', 'get')));
        $finder->addRoute(new Route('POST', '/subpath/(numeric)/(string)', new Call('index', 'post')));
        $finder->addRoute(new Route('GET', '/(string)/(numeric)/subpath', new Call('index', 'get')));
        //REST
        $finder->addRoute(new Route('GET', '/user/(numeric)', new Call('controller', 'retrieve')));
        $finder->addRoute(new Route('GET', '/user', new Call('controller', 'list')));
        $finder->addRoute(new Route('POST', '/user', new Call('controller', 'create')));
        $finder->addRoute(new Route('PUT', '/user/(numeric)', new Call('controller', 'update')));
        $finder->addRoute(new Route('DELETE', '/user/(numeric)', new Call('controller', 'delete')));

        $route = $finder->findRoute(new Request('GET', '/'));
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/', $route->getUri());
        $this->assertEquals('index', $route->getCall()->getController());
        $this->assertEquals('get', $route->getCall()->getMethod());

        $route = $finder->findRoute(new Request('POST', '/subpath/123/tim'));
        $this->assertEquals('POST', $route->getHttpMethod());
        $this->assertEquals('/subpath/(numeric)/(string)', $route->getUri());
        $this->assertEquals('index', $route->getCall()->getController());
        $this->assertEquals('post', $route->getCall()->getMethod());

        $route = $finder->findRoute(new Request('POST', '/subpath/123.34/tim'));
        $this->assertEquals('POST', $route->getHttpMethod());
        $this->assertEquals('/subpath/(numeric)/(string)', $route->getUri());
        $this->assertEquals('index', $route->getCall()->getController());
        $this->assertEquals('post', $route->getCall()->getMethod());

        $route = $finder->findRoute(new Request('GET', '/tim/123/subpath?id=1&name=test'));
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/(string)/(numeric)/subpath', $route->getUri());
        $this->assertEquals('index', $route->getCall()->getController());
        $this->assertEquals('get', $route->getCall()->getMethod());

        //REST
        //create
        $route = $finder->findRoute(new Request('POST', '/user'));
        $this->assertEquals('POST', $route->getHttpMethod());
        $this->assertEquals('/user', $route->getUri());
        $this->assertEquals('controller', $route->getCall()->getController());
        $this->assertEquals('create', $route->getCall()->getMethod());
        //list
        $route = $finder->findRoute(new Request('GET', '/user'));
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/user', $route->getUri());
        $this->assertEquals('controller', $route->getCall()->getController());
        $this->assertEquals('list', $route->getCall()->getMethod());
        //retrieve
        $route = $finder->findRoute(new Request('GET', '/user/1'));
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/user/(numeric)', $route->getUri());
        $this->assertEquals('controller', $route->getCall()->getController());
        $this->assertEquals('retrieve', $route->getCall()->getMethod());
        //update
        $route = $finder->findRoute(new Request('PUT', '/user/1'));
        $this->assertEquals('PUT', $route->getHttpMethod());
        $this->assertEquals('/user/(numeric)', $route->getUri());
        $this->assertEquals('controller', $route->getCall()->getController());
        $this->assertEquals('update', $route->getCall()->getMethod());
        //delete
        $route = $finder->findRoute(new Request('DELETE', '/user/1'));
        $this->assertEquals('DELETE', $route->getHttpMethod());
        $this->assertEquals('/user/(numeric)', $route->getUri());
        $this->assertEquals('controller', $route->getCall()->getController());
        $this->assertEquals('delete', $route->getCall()->getMethod());
        $this->assertEquals('delete', $route->getCall()->getMethod());
    }

    public function testFindException()
    {
        $finder = new Router();
        $finder->addRoute(new Route('GET', '/', new Call('index', 'get')));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException');
        $finder->findRoute(new Request('PUT', '/'));
    }

    public function testExecuteExceptionNoRouteAvailable()
    {
        $finder = new Router();
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException', 'No route available');
        $finder->findRoute(new Request('GET', '/'));
    }
}
