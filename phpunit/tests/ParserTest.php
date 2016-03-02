<?php
namespace TimTegeler\Routerunner;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRoute()
    {
        $parser = new Parser('\\');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('*      /                                     index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /                                     index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /                                     index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath#anchor                       index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath#anchor                       index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/subpath                      index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/subpath                      index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /[string]                             index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /[string]                             index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /[string]/[numeric]                   index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /[string]/[numeric]                   index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /[numeric]/[string]                   index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /[numeric]/[string]                   index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /[string]/[numeric]/subpath           index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /[string]/[numeric]/subpath           index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /[numeric]/[string]/subpath           index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /[numeric]/[string]/subpath           index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/[string]/[numeric]           index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/[string]/[numeric]           index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/[numeric]/[string]           index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/[numeric]/[string]           index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/[numeric]/[string]#anchor    index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/[numeric]/[string]#anchor    index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath#anchor                       index->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath#anchor                       index->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/subpath                      c_controller->get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/subpath                      c_controller->post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/[numeric]                    c_controller->_get'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/[numeric]                    c_controller->_post'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET    /subpath/[numeric]                    c_controller->_get1'));
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST   /subpath/[numeric]                    c_controller->_post1'));
    }

    public function testCreateRouteExpetion()
    {
        $parser = new Parser('\\');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('/ index->get'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST / index'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET index->get'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('DELETE /subpath index->post'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('GET /(string) index->get'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST /(string)/(numeric) index->post'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST /[string]/[numeric] _index->post'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $this->assertInstanceOf('TimTegeler\Routerunner\Route', $parser->createRoute('POST /[string]/[numeric] _index->post9'));
    }

    public function testParse()
    {
        $parser = new Parser('\\');
        $parser->setCaching(false);
        $routes = $parser->parse(__DIR__ . '/../assets/routes');
        /** @var Route $route */
        $route = $routes[0];
        $this->assertEquals('GET', $route->getHttpMethod());
        $this->assertEquals('/', $route->getUri());
        $this->assertEquals('\Index', $route->getCallback()->getController());
        $this->assertEquals('get', $route->getCallback()->getMethod());
    }

    public function testCaching()
    {
        $parser = new Parser('\\');
        $parser->setCaching(true);
        $parser->getCache()->setFile(__DIR__ . '/../assets/cache');
        $parser->parse(__DIR__ . '/../assets/routes');

        $newRoute = 'POST /example Index->post';
        file_put_contents(__DIR__ . '/../assets/routes', $newRoute, FILE_APPEND);

        $parser->parse(__DIR__ . '/../assets/routes');

        $this->removeLastLine(__DIR__ . '/../assets/routes');
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

    public function testParseException()
    {
        $parser = new Parser('\\');
        $parser->setCaching(false);
        $this->setExpectedException('TimTegeler\Routerunner\Exception\ParseException');
        $parser->parse('not/existing/path');
    }
}
