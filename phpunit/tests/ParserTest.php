<?php
namespace TimTegeler\Routerunner;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRoute()
    {
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("*      /                           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /                           index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /                           index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /subpath                    index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /subpath                    index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /subpath/subpath            index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /subpath/subpath            index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /[string]                   index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /[string]                   index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /[string]/[numeric]         index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /[string]/[numeric]         index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /[numeric]/[string]         index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /[numeric]/[string]         index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /[string]/[numeric]/subpath index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /[string]/[numeric]/subpath index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /[numeric]/[string]/subpath index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /[numeric]/[string]/subpath index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /subpath/[string]/[numeric] index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /subpath/[string]/[numeric] index->post"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET    /subpath/[numeric]/[string] index->get"));
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST   /subpath/[numeric]/[string] index->post"));
    }

    public function testCreateRouteExpetion()
    {
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("/ index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST / index"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("DELETE /subpath index->post"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("GET /(string) index->get"));
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        $this->assertInstanceOf("TimTegeler\\Routerunner\\Route", Parser::createRoute("POST /(string)/(numeric) index->post"));
    }

    public function testParse()
    {
        Parser::setCaching(false);
        $routes = Parser::parse(__DIR__ . "/../assets/routes");
        /** @var Route $route */
        $route = $routes[0];
        $this->assertEquals("GET", $route->getHttpMethod());
        $this->assertEquals("/", $route->getUri());
        $this->assertEquals("index->get", $route->getCallable());
    }

//    public function testCachingPerformance()
//    {
//        Parser::setCaching(false);
//        $starttime = microtime();
//        Parser::parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithoutCaching = $endtime - $starttime;
//
//        Parser::setCaching(true);
//        $starttime = microtime();
//        Parser::parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithCacheWrite = $endtime - $starttime;
//        $this->assertGreaterThan($parsingTimeWithoutCaching, $parsingTimeWithCacheWrite);
//
//        $starttime = microtime();
//        Parser::parse(__DIR__ . "/../assets/routes");
//        $endtime = microtime();
//        $parsingTimeWithCacheRead = $endtime - $starttime;
//        $this->assertLessThan($parsingTimeWithCacheWrite, $parsingTimeWithCacheRead);
//        Cache::clear();
//    }

    public function testParseException()
    {
        Parser::setCaching(false);
        $this->setExpectedException("TimTegeler\\Routerunner\\Exception\\ParseException");
        Parser::parse("not/existing/path");
    }
}
