<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Mock\Encoder;
use TimTegeler\Routerunner\Mock\LoginFalse;
use TimTegeler\Routerunner\Mock\LoginTrue;

class RouterunnerTest extends \PHPUnit_Framework_TestCase
{

    public function testExecuteWithParseFallback()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml');
        $this->assertEquals('index->get', $routerunner->execute('PUST', '/123/tim'));
    }

    public function testMiddlewareTrue()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml');
        //$routerunner->route('GET', '/(numeric)/(string)', 'Index->get');
        //$routerunner->route('POST', '/(numeric)/(string)', 'Index->post');
        $loginMiddleware = new LoginTrue('TimTegeler\Routerunner\Mock\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals('index->get', $routerunner->execute('GET', '/123/tim'));
        $this->assertEquals('index->post', $routerunner->execute('POST', '/123/tim'));
    }

    public function testMiddlewareLoginFalse()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml');
        $loginMiddleware = new LoginFalse('TimTegeler\Routerunner\Mock\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals('index->login', $routerunner->execute('GET', '/123/tim'));
        $this->assertEquals('index->login', $routerunner->execute('POST', '/123/tim'));
    }

    public function testPostprocessing()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml');
        $routerunner->setPostProcessor(new Encoder());
        $this->assertEquals('{"index":"login"}', $routerunner->execute('GET', '/api'));
    }
    

}
