<?php
namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Components\Call;
use TimTegeler\Routerunner\Controller\ControllerInterface;
use TimTegeler\Routerunner\Controller\RestControllerInterface;
use TimTegeler\Routerunner\Controller\RetrieveControllerInterface;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

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
        $loginMiddleware = new LoginTrue('TimTegeler\Routerunner\Index', 'login');
        $routerunner->registerMiddleware($loginMiddleware);
        $this->assertEquals('index->get', $routerunner->execute('GET', '/123/tim'));
        $this->assertEquals('index->post', $routerunner->execute('POST', '/123/tim'));
        //REST
        $this->assertEquals('TimTegeler\Routerunner\User->_create', $routerunner->execute('POST', '/user'));
        $this->assertEquals('TimTegeler\Routerunner\User->_retrieve', $routerunner->execute('GET', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\User->_update', $routerunner->execute('PUT', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\User->_delete', $routerunner->execute('DELETE', '/user/1'));
        $this->assertEquals('TimTegeler\Routerunner\User->_list', $routerunner->execute('GET', '/user'));
        $this->assertEquals('TimTegeler\Routerunner\Group->_retrieve', $routerunner->execute('GET', '/group/1'));
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->execute('POST', '/group');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->execute('PUT', '/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->execute('DELETE', '/group/1');
        $this->setExpectedException('TimTegeler\Routerunner\Exception\DispatcherException', 'Method can not be found.');
        $routerunner->execute('GET', '/group');
    }

    public function testMiddlewareLoginFalse()
    {
        $routerunner = new Routerunner(__DIR__ . '/../assets/config.yml');
        $loginMiddleware = new LoginFalse('TimTegeler\Routerunner\Index', 'login');
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

/**
 * Class Encoder
 * @package TimTegeler\Routerunner
 */
class Encoder implements PostProcessorInterface
{

    /**
     * @param $return
     * @return string
     */
    public function process($return)
    {
        return json_encode($return);
    }
}

class LoginTrue extends Middleware
{

}

class LoginFalse extends Middleware
{

    /**
     * @param Call $call
     * @return bool
     */
    public function process($call)
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
     * @return string
     */
    public function _create()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _delete($id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @return string
     */
    public function _list()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _retrieve($id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _update($id)
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
     * @param int $id
     * @return string
     */
    public function _retrieve($id)
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
