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
        $loginMiddleware = new LoginTrue('TimTegeler\Routerunner\Mock\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals('index->get', $routerunner->execute('GET', '/123/tim'));
        $this->assertEquals('index->post', $routerunner->execute('POST', '/123/tim'));
        //REST
        $this->assertEquals('TimTegeler\Routerunner\Mock\User->_create', $routerunner->execute('POST', '/user'));
        $this->assertEquals('TimTegeler\Routerunner\Mock\User->_retrieve', $routerunner->execute('GET', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\Mock\User->_update', $routerunner->execute('PUT', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\Mock\User->_delete', $routerunner->execute('DELETE', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\Mock\User->_list', $routerunner->execute('GET', '/user'));
        $this->assertEquals('TimTegeler\Routerunner\Mock\Group->_retrieve', $routerunner->execute('GET', '/group/1'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException', 'Method can not be found.');
        $routerunner->execute('POST', '/group');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException', 'Method can not be found.');
        $routerunner->execute('PUT', '/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException', 'Method can not be found.');
        $routerunner->execute('DELETE', '/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\RouterException', 'Method can not be found.');
        $routerunner->execute('GET', '/group');
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
