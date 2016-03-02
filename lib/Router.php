<?php

namespace TimTegeler\Routerunner;

use DI\Container;
use ReflectionMethod;
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
     * @var string
     */
    const FALLBACK_HTTP_METHOD = "GET";
    /**
     * @var string
     */
    const FALLBACK_URI = "/";
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
     * Router constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
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
        $route = self::findRoute($httpMethod, $uri);
        $callback = $route->getCallback();
        $method = $callback->getMethod();

        $controller = self::constructController($callback->getController());

        foreach ($this->middlewares as $middleware) {
            /** @var Middleware $middleware */
            if ($middleware->process($controller) === false) {
                $callback = $middleware->getCallback();
                $method = $callback->getMethod();
                $controller = self::constructController($callback->getController());
                break;
            }
        }

        if (method_exists($controller, $method)) {
            $refMethod = new ReflectionMethod($callback->getController(), $method);
            $return = $refMethod->invokeArgs($controller, $route->getParameter());

            if ($this->postProcessor != null) {
                return $this->postProcessor->process($return);
            } else {
                return $return;
            }
        } else {
            throw new RouterException("Route is not callable");
        }

    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return Route
     * @throws RouterException
     */
    private function findRoute($httpMethod, $uri)
    {
        try {
            $route = $this->finder->findRoute($httpMethod, $uri);
        } catch (RouterException $e) {
            $route = $this->finder->findRoute(self::FALLBACK_HTTP_METHOD, self::FALLBACK_URI);
        }
        return $route;
    }

    /**
     * @param $class
     * @return object
     * @throws RouterException
     */
    private function constructController($class)
    {
        if (class_exists($class)) {
            $controller = $this->container->get($class);
            return $controller;
        } else {
            throw new RouterException("Route is not callable");
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

}