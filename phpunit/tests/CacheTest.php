<?php
namespace TimTegeler\Routerunner;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testExistAndReadableAndWriteable()
    {
        Cache::setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue(Cache::useable());
        Cache::clear();
        Cache::setFile("notExistingFile");
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\CacheException");
        Cache::useable();
    }

    public function testSave()
    {
        Cache::setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue(Cache::useable());
        $routes = array();
        $routes[] = new Route("*", "/", "foo->bar");
        $routes[] = new Route("GET", "/", "foo->bar");
        $routes[] = new Route("POST", "/subpath/[numeric]/[string]", "foo->bar");
        Cache::write($routes);
        $this->assertEquals($routes, Cache::read());
        Cache::clear();
    }

}
