<?php

namespace TimTegeler\Routerunner;

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;
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
     * @param $controllerRootNameSpace
     * @param ContainerInterface $container
     */
    public function __construct($controllerRootNameSpace = null, ContainerInterface $container = null)
    {
        if ($container == null) {
            $container = ContainerBuilder::buildDevContainer();
        }
        $this->router = new Router($container);
        $this->parser = new Parser($controllerRootNameSpace);
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
     * @param $call
     * @throws Exception\ParseException
     */
    public function route($httpMethod, $uri, $call)
    {
        $this->router->getFinder()->addRoute($this->parser->createRoute($httpMethod, $uri, $call));
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

    /**
     * @param $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->router->getFinder()->setBaseUri($baseUri);
    }
}