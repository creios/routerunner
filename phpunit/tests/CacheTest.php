<?php
namespace TimTegeler\Routerunner;

use phpFastCache\CacheManager;
use TimTegeler\Routerunner\Util\Cache;
use TimTegeler\Routerunner\Util\Call;
use TimTegeler\Routerunner\Util\Route;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testSave()
    {
        $cache = new Cache(CacheManager::Files(), 'routerunner_cache');

        $routes = array();
        $routes[] = new Route('*', '/', new Call('foo', 'bar'));
        $routes[] = new Route('GET', '/', new Call('foo', 'bar'));
        $routes[] = new Route('POST', '/subpath/(numeric)/(string)', new Call('foo', 'bar'));
        $cache->write($routes);
        $this->assertEquals($routes, $cache->read());
        $cache->clear();
    }

}
