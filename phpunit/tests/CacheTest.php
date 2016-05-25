<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Util\Cache;
use TimTegeler\Routerunner\Util\Call;
use TimTegeler\Routerunner\Util\Route;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testNotExist()
    {
        $cache = new Cache();
        $cache->setFile(__DIR__ . '/../assets/cache');
        $this->assertTrue($cache->useable());
        $cache->clear();
        $cache->setFile('notExistingFile');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\CacheException', "File (notExistingFile) doesn't exist.");
        $cache->useable();
    }

//    public function testNotReadable()
//    {
//        $cache = new Cache();
//        $cache->setFile(__DIR__ . '/../assets/noReadable');
//        $this->setExpectedException('TimTegeler\Routerunner\Exception\CacheException', 'File (' . __DIR__ . '/../assets/noReadable) isn't readable.');
//        $cache->useable();
//    }
//
//    public function testNotWriteable()
//    {
//        $cache = new Cache();
//        $cache->setFile(__DIR__ . '/../assets/noWriteable');
//        $this->setExpectedException('TimTegeler\Routerunner\Exception\CacheException', 'File (' . __DIR__ . '/../assets/noWriteable) isn't writeable.');
//        $cache->useable();
//    }

    public function testSave()
    {
        $cache = new Cache();

        $cache->setFile(__DIR__ . '/../assets/cache');
        $this->assertTrue($cache->useable());
        $routes = array();
        $routes[] = new Route('*', '/', new Call('foo', 'bar'));
        $routes[] = new Route('GET', '/', new Call('foo', 'bar'));
        $routes[] = new Route('POST', '/subpath/(numeric)/(string)', new Call('foo', 'bar'));
        $cache->write($routes);
        $this->assertEquals($routes, $cache->read());
        $cache->clear();
    }

}
