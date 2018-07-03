<?php

namespace TimTegeler\Routerunner;

use DI\Container;
use DI\ContainerBuilder;
use phpFastCache\CacheManager;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TimTegeler\Routerunner\Components\Cache;
use TimTegeler\Routerunner\Components\Call;
use TimTegeler\Routerunner\Controller\ControllerInterface;
use TimTegeler\Routerunner\Controller\RestControllerInterface;
use TimTegeler\Routerunner\Controller\RetrieveControllerInterface;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\Processor\PostProcessorInterface;
use TimTegeler\Routerunner\Processor\PreProcessorInterface;

class RouterunnerTest extends \PHPUnit_Framework_TestCase
{

    /** @var PHPUnit_Framework_MockObject_MockObject|ServerRequestInterface */
    private $serverRequest;
    /** @var PHPUnit_Framework_MockObject_MockObject|RequestHandlerInterface */
    private $requestHandler;
    /**
     * @var Container
     */
    private $diContainer;

    public function setUp()
    {
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->diContainer = ContainerBuilder::buildDevContainer();
        $this->diContainer->set(Cache::class, function(){
           return new Cache(CacheManager::Files(), 'routerunner_cache');
        });
    }

    /**
     * @throws Exception\DispatcherException
     * @throws Exception\ParseException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function testExecuteWithParseFallback()
    {
        $this->serverRequest->method('getMethod')->willReturn('PUST');
        $this->serverRequest->method('getRequestTarget')->willReturn('/123/tim');
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml', $this->diContainer);
        $this->assertEquals('index->get', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());
    }

    /**
     * @throws Exception\DispatcherException
     * @throws Exception\ParseException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function testMiddlewareTrue()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml', $this->diContainer);
        $loginMiddleware = new LoginTrue('TimTegeler\Routerunner\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/123/tim');
        $this->assertEquals('index->get', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('POST');
        $this->serverRequest->method('getRequestTarget')->willReturn('/123/tim');
        $this->assertEquals('index->post', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());
        //REST
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('POST');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user');
        $this->assertEquals('TimTegeler\Routerunner\User->_create', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('PUT');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/1');
        $this->assertEquals('TimTegeler\Routerunner\User->_update', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('DELETE');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/1');
        $this->assertEquals('TimTegeler\Routerunner\User->_delete', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/1');
        $this->assertEquals('TimTegeler\Routerunner\User->_retrieve', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user');
        $this->assertEquals('TimTegeler\Routerunner\User->_list', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('PUT');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/john');
        $this->assertEquals('TimTegeler\Routerunner\User->_update', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('DELETE');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/john');
        $this->assertEquals('TimTegeler\Routerunner\User->_delete', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/user/john');
        $this->assertEquals('TimTegeler\Routerunner\User->_retrieve', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('withAttribute')->willReturn($this->serverRequest);
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/group/1');
        $this->assertEquals('TimTegeler\Routerunner\Group->_retrieve', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('POST');
        $this->serverRequest->method('getRequestTarget')->willReturn('/group');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->process($this->serverRequest, $this->requestHandler);

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('PUT');
        $this->serverRequest->method('getRequestTarget')->willReturn('/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->process($this->serverRequest, $this->requestHandler);

        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('DELETE');
        $this->serverRequest->method('getRequestTarget')->willReturn('/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->process($this->serverRequest, $this->requestHandler);
    }

    /**
     * @throws Exception\DispatcherException
     * @throws Exception\ParseException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function testMiddlewareLoginFalse()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml', $this->diContainer);
        $loginMiddleware = new LoginFalse('TimTegeler\Routerunner\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('DELETE');
        $this->serverRequest->method('getRequestTarget')->willReturn('/123/tim');
        $this->assertEquals('index->login', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('POST');
        $this->serverRequest->method('getRequestTarget')->willReturn('/123/tim');
        $this->assertEquals('index->login', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());
    }

    /**
     * @throws Exception\DispatcherException
     * @throws Exception\ParseException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function testProcessing()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml', $this->diContainer);
        $routerunner->setPreProcessor(new PreProcessor());
        $routerunner->setPostProcessor(new PostProcessor());
        $this->serverRequest = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->serverRequest->method('getMethod')->willReturn('GET');
        $this->serverRequest->method('getRequestTarget')->willReturn('/api');
        $this->assertEquals('{"index":"login"}', $routerunner->process($this->serverRequest, $this->requestHandler)->getBody()->getContents());
    }

}

/**
 * Class PreProcessor
 * @package TimTegeler\Routerunner
 */
class PreProcessor implements PreProcessorInterface
{

    /**
     * @param ServerRequestInterface $serverRequest
     * @param ControllerInterface $controller
     * @return ServerRequestInterface
     */
    public function process(ServerRequestInterface $serverRequest, ControllerInterface $controller)
    {
        return $serverRequest;
    }
}

/**
 * Class PostProcessor
 * @package TimTegeler\Routerunner
 */
class PostProcessor implements PostProcessorInterface
{

    /**
     * @param ServerRequestInterface $serverRequest
     * @param mixed $return
     * @return string
     */
    public function process(ServerRequestInterface $serverRequest, $return)
    {
        return json_encode($return);
    }
}

class LoginTrue extends Middleware
{

    public function __construct($controllerName, $methodName)
    {
        parent::__construct(new Call($controllerName, $methodName));
    }
}

class LoginFalse extends Middleware
{

    public function __construct($controllerName, $methodName)
    {
        parent::__construct(new Call($controllerName, $methodName));
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @param Call $call
     * @return bool
     */
    public function process(ServerRequestInterface $serverRequest, $call)
    {
        return false;
    }
}

/**
 * Class User
 * @package TimTegeler\Routerunner
 */
class User implements RestControllerInterface
{

    /**
     * @param string $path
     */
    public function setReroutedPath($path)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function _create(ServerRequestInterface $request)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return string
     */
    public function _delete(ServerRequestInterface $request, $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function _list(ServerRequestInterface $request)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return string
     */
    public function _retrieve(ServerRequestInterface $request, $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return string
     */
    public function _update(ServerRequestInterface $request, $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }
}

/**
 * Class Group
 * @package TimTegeler\Routerunner
 */
class Group implements RetrieveControllerInterface
{

    /**
     * @param string $path
     */
    public function setReroutedPath($path)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return string
     */
    public function _retrieve(ServerRequestInterface $request, $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }
}

/**
 * Class Index
 * @package TimTegeler\Routerunner
 */
class Index implements ControllerInterface
{

    /**
     * Index constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public static function get()
    {
        return "index->get";
    }

    /**
     * @return string
     */
    public static function post()
    {
        return "index->post";
    }

    /**
     * @return string
     */
    public static function login()
    {
        return "index->login";
    }

    /**
     * @return array
     */
    public static function api()
    {
        return ['index' => 'login'];
    }

    /**
     * @param string $path
     */
    public function setReroutedPath($path)
    {
    }

}
