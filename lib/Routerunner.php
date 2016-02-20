<?php

namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Routerunner
 * @package TimTegeler\Routerunner
 */
class Routerunner
{

    /**
     * @var Router
     */
    private $router;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * Routerunner constructor.
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->parser = new Parser();
    }

    /**
     * @param $filename
     * @throws Exception\ParseException
     */
    public function parse($filename)
    {
        $routes = $this->parser->parse($filename);
        $this->router->getFinder()->addRoutes($routes);
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @param $callback
     * @throws Exception\ParseException
     */
    public function route($httpMethod, $uri, $callback)
    {
        $routeFormat = "%s %s %s";
        $route = sprintf($routeFormat, $httpMethod, $uri, $callback);
        $this->router->getFinder()->addRoute($this->parser->createRoute($route));
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws RouterException
     */
    public function execute($httpMethod, $uri)
    {
        return $this->router->execute($httpMethod, $uri);
    }

    /**
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->router->registerMiddleware($middleware);
    }

    /**
     * @param PostProcessorInterface $postProcessor
     */
    public function setPostProcessor($postProcessor)
    {
        $this->router->setPostProcessor($postProcessor);
    }

    /**
     * @param $callableNameSpace
     */
    public function setCallableNameSpace($callableNameSpace)
    {
        $this->parser->setCallableNameSpace($callableNameSpace);
    }

    /**
     * @param array $controllerDependencies
     */
    public function setControllerDependencies(array $controllerDependencies)
    {
        $this->router->setControllerDependencies($controllerDependencies);
    }

    /**
     * @param $enable
     */
    public function setCaching($enable)
    {
        $this->parser->setCaching($enable);
    }

    /**
     * @param $path
     */
    public function setCacheFile($path)
    {
        $this->parser->getCache()->setFile($path);
    }
}