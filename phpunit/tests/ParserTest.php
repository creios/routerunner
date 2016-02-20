<?php
namespace TimTegeler\Routerunner;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRoute()
    {
        $parser = new Parser();
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("*      /                                     index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /                                     index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /                                     index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath#anchor                       index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath#anchor                       index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/subpath                      index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/subpath                      index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /[string]                             index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /[string]                             index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /[string]/[numeric]                   index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /[string]/[numeric]                   index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /[numeric]/[string]                   index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /[numeric]/[string]                   index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /[string]/[numeric]/subpath           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /[string]/[numeric]/subpath           index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /[numeric]/[string]/subpath           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /[numeric]/[string]/subpath           index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/[string]/[numeric]           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/[string]/[numeric]           index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/[numeric]/[string]           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/[numeric]/[string]           index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/[numeric]/[string]#anchor    index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/[numeric]/[string]#anchor    index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath#anchor                       index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath#anchor                       index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/subpath                      c_controller->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/subpath                      c_controller->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/[numeric]                    c_controller->_get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/[numeric]                    c_controller->_post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET    /subpath/[numeric]                    c_controller->_get1"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST   /subpath/[numeric]                    c_controller->_post1"));
    }

    public function testCreateRouteExpetion()
    {
        $parser = new Parser();
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("/ index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST / index"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("DELETE /subpath index->post"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("GET /(string) index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST /(string)/(numeric) index->post"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST /[string]/[numeric] _index->post"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", $parser->createRoute("POST /[string]/[numeric] _index->post9"));
    }

    public function testParse()
    {
        $parser = new Parser();
        $parser->setCaching(false);
        $routes = $parser->parse(__DIR__ . "/../assets/routes");
        /** @var Route $route */
        $route = $routes[0];
        $this->assertEquals("GET", $route->getHttpMethod());
        $this->assertEquals("/", $route->getUri());
        $this->assertEquals('\Index', $route->getCallback()->getController());
        $this->assertEquals("get", $route->getCallback()->getMethod());
    }

//    public function testCachingPerformance()
//    {
//
//        $parser->setCaching(false);
//        $starttime = microtime();
//        $parser->parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithoutCaching = $endtime - $starttime;
//        echo $parsingTimeWithoutCaching." ";
//
//        $parser->setCaching(true);
//        Cache::setFile(__DIR__ . "/../assets/cache");
//        Cache::clear();
//        $starttime = microtime();
//        $parser->parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithCacheWrite = $endtime - $starttime;
//        $this->assertGreaterThan($parsingTimeWithoutCaching, $parsingTimeWithCacheWrite);
//        echo $parsingTimeWithCacheWrite." ";
//
//        $starttime = microtime();
//        $parser->parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithCacheRead = $endtime - $starttime;
//        $this->assertLessThan($parsingTimeWithCacheWrite, $parsingTimeWithCacheRead);
//        echo $parsingTimeWithCacheRead."\r\n";
//        //Cache::clear();
//    }

    public function testParseException()
    {
        $parser = new Parser();
        $parser->setCaching(false);
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $parser->parse("not/existing/path");
    }
}
