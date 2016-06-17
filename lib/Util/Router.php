<?php

namespace TimTegeler\Routerunner\Util;

use DI\Container;
use Interop\Container\ContainerInterface;
use ReflectionMethod;
use TimTegeler\Routerunner\Controller\ControllerInterface;
use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Router
 * @package TimTegeler\Routerunner
 */
class Router
{

    /**
     * @var array
     */
    private $middlewares = [];
    /**
     * @var PostProcessorInterface
     */
    private $postProcessor;
    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var Call
     */
    private $fallback;

    /**
     * Router constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->finder = new Finder();
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws RouterException
     */
    public function execute($httpMethod, $uri)
    {
        try {
            $route = $this->finder->findRoute($httpMethod, $uri);
            $call = $route->getCall();
            $method = $call->getMethod();
            $parameter = $route->getParameter();
            $controller = self::constructController($call->getController());
        } catch (RouterException $e) {
            $call = $this->fallback;
            $method = $call->getMethod();
            $parameter = [];
            $controller = self::constructController($call->getController());
            $controller->setReroutedUri($uri);
        }

        foreach ($this->middlewares as $middleware) {
            /** @var Middleware $middleware */
            if ($middleware->process($controller) === false) {
                $call = $middleware->getCall();
                $method = $call->getMethod();
                $controller = self::constructController($call->getController());
                $controller->setReroutedUri($uri);
                break;
            }
        }

        if (method_exists($controller, $method)) {
            $refMethod = new ReflectionMethod($call->getController(), $method);
            $return = $refMethod->invokeArgs($controller, $parameter);

            if ($this->postProcessor != null) {
                return $this->postProcessor->process($return);
            } else {
                return $return;
            }
        } else {
            throw new RouterException("Method can not be found.");
        }

    }

    /**
     * @param $class
     * @return ControllerInterface
     * @throws RouterException
     */
    private function constructController($class)
    {
        if (class_exists($class)) {
            $controller = $this->container->get($class);
            return $controller;
        } else {
            throw new RouterException("Controller can not be found.");
        }
    }

    /**
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @param PostProcessorInterface $postProcessor
     */
    public function setPostProcessor($postProcessor)
    {
        $this->postProcessor = $postProcessor;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * @param Call $fallback
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;
    }

}