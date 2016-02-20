<?php
namespace TimTegeler\Routerunner;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testExistAndReadableAndWriteable()
    {
        $cache = new Cache();
        
        $cache->setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue($cache->useable());
        $cache->clear();
        $cache->setFile("notExistingFile");
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\CacheException");
        $cache->useable();
    }

    public function testSave()
    {
        $cache = new Cache();

        $cache->setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue($cache->useable());
        $routes = array();
        $routes[] = new Route("*", "/", new Callback("foo", "bar"));
        $routes[] = new Route("GET", "/", new Callback("foo", "bar"));
        $routes[] = new Route("POST", "/subpath/[numeric]/[string]", new Callback("foo", "bar"));
        $cache->write($routes);
        $this->assertEquals($routes, $cache->read());
        $cache->clear();
    }

}
