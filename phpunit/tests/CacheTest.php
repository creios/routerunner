<?php
namespace TimTegeler\Routerunner;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testExistAndReadableAndWriteable()
    {
        Cache::setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue(Cache::exist());
        $this->assertTrue(Cache::readable());
        $this->assertTrue(Cache::writeable());
        Cache::clear();
        Cache::setFile("notExistingFile");
        $this->assertFalse(Cache::exist());
        $this->assertFalse(Cache::readable());
        $this->assertFalse(Cache::writeable());
    }

    public function testSave()
    {
        Cache::setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue(Cache::exist());
        $routes = array();
        $routes[] = new Route("*", "/", "foo->bar");
        $routes[] = new Route("GET", "/", "foo->bar");
        $routes[] = new Route("POST", "/subpath/[numeric]/[string]", "foo->bar");
        Cache::save($routes);
        $this->assertEquals($routes, Cache::load());
        Cache::clear();
    }

}
