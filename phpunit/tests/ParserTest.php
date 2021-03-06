<?php
namespace TimTegeler\Routerunner;

use phpFastCache\CacheManager;
use TimTegeler\Routerunner\Components\Cache;
use TimTegeler\Routerunner\Components\Parser;
use TimTegeler\Routerunner\Components\Route;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRoute()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('*', '/', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath#anchor', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath#anchor', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/subpath', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/subpath', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/(string)', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/(string)', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/(string)/(numeric)', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/(string)/(numeric)', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/(numeric)/(string)', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/(numeric)/(string)', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/(string)/(numeric)/subpath', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/(string)/(numeric)/subpath', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/(numeric)/(string)/subpath', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/(numeric)/(string)/subpath', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(string)/(numeric)', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/(string)/(numeric)', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(numeric)/(string)', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/(numeric)/(string)', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(numeric)/(string)#anchor', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/(numeric)/(string)#anchor', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath#anchor', 'index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath#anchor', 'index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/subpath', 'c_controller->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/subpath', 'c_controller->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(numeric)', 'c_controller->_get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/(numeric)', 'c_controller->_post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(numeric)', 'c_controller->_get1'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath/(numeric)', 'c_controller->_post1'));
        //REST
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath/(numeric)', 'c_controller->retrieve'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('GET', '/subpath', 'c_controller->list'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('POST', '/subpath', 'c_controller->create'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('PUT', '/subpath/(numeric)', 'c_controller->update'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Components\Route', $parser->createRoute('DELETE', '/subpath/(numeric)', 'c_controller->delete'));
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParse()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $config = $parser->parse(__DIR__ . '/../assets/config.yml');
        /** @var Route $route */
        $route = $config->getRoutes()[0];
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/', $route->getUri());
        $this->assertEquals('TimTegeler\Routerunner\Index', $route->getCall()->getController());
        $this->assertEquals('get', $route->getCall()->getMethod());
    }

    /**
     * @throws Exception\ParseException
     */
    public function testCaching()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(true);
        $parser->parse(__DIR__ . '/../assets/config.yml');

        $newRoute = 'POST /example Index->post';
        file_put_contents(__DIR__ . '/../assets/config.yml', $newRoute, FILE_APPEND);

        $parser->parse(__DIR__ . '/../assets/config.yml');

        $this->removeLastLine(__DIR__ . '/../assets/config.yml');
    }

    private function removeLastLine($fileName)
    {
        // load the data and delete the line from the array
        $lines = file($fileName);
        $last = sizeof($lines) - 1;
        unset($lines[$last]);

        // write the new data to the file
        $fp = fopen($fileName, 'w');
        fwrite($fp, implode('', $lines));
        fclose($fp);
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoConfig()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'File doesn\'t exist');
        $parser->parse('not/existing/path');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoRoutes()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'Config doesn\'t have a routes section');
        $parser->parse(__DIR__ . '/../assets/config-no-routes.yml');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoFallback()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'Config doesn\'t have a fallback');
        $parser->parse(__DIR__ . '/../assets/config-no-fallback.yml');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoBaseNamespace()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'Config doesn\'t have a baseNamespace');
        $parser->parse(__DIR__ . '/../assets/config-no-basenamespace.yml');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoValidBaseNamespace()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'BaseNamespace is not a valid namespace');
        $parser->parse(__DIR__ . '/../assets/config-no-valid-basenamespace.yml');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionNoValidBasePath()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'BasePath is not a valid path');
        $parser->parse(__DIR__ . '/../assets/config-no-valid-basepath.yml');
    }

    /**
     * @throws Exception\ParseException
     */
    public function testParseExceptionRouteIsIncomplete()
    {
        $parser = new Parser(new Cache(CacheManager::Files(), 'routerunner_cache'));
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException', 'Route 1 is incomplete');
        $parser->parse(__DIR__ . '/../assets/config-route-incomplete.yml');
    }
}
