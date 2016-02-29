<?php
namespace TimTegeler\Routerunner;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testNotExist()
    {
        $cache = new Cache();
        $cache->setFile(__DIR__ . "/../assets/cache");
        $this->assertTrue($cache->useable());
        $cache->clear();
        $cache->setFile("notExistingFile");
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\CacheException", "File (notExistingFile) doesn't exist.");
        $cache->useable();
    }

//    public function testNotReadable()
//    {
//        $cache = new Cache();
//        $cache->setFile(__DIR__ . "/../assets/noReadable");
//        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\CacheException", "File (" . __DIR__ . "/../assets/noReadable) isn't readable.");
//        $cache->useable();
//    }
//
//    public function testNotWriteable()
//    {
//        $cache = new Cache();
//        $cache->setFile(__DIR__ . "/../assets/noWriteable");
//        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\CacheException", "File (" . __DIR__ . "/../assets/noWriteable) isn't writeable.");
//        $cache->useable();
//    }

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
