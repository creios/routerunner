<?php

namespace TimTegeler\Routerunner\Components;

use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;

/**
 * Class Router
 * @package TimTegeler\Routerunner
 */
class Router
{

    /**
     * @var array
     */
    private static $httpMethodSearch = ["*"];

    /**
     * @var array
     */
    private static $httpMethodReplace = ["GET|POST|PUT|DELETE"];

    /**
     * @var array
     */
    private static $uriSearch = ['/', "(string)", "(numeric)"];

    /**
     * @var array
     */
    private static $uriReplace = ['\/', '(\w+)', '(\d+|\d+\.\d+)'];

    /**
     * @var array
     */
    private $middlewares = [];

    /**
     * @var Call
     */
    private $fallback;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var string
     */
    private $basePath;

    /**
     * @param Request $request
     * @return Execution
     */
    public function route(Request $request)
    {
        $hasBeenRerouted = false;
        try {
            $route = $this->findRoute($request);
            $call = $route->getCall();
            $parameter = $route->getParameter();
        } catch (RouterException $e) {
            $call = $this->fallback;
            $parameter = [];
            $hasBeenRerouted = true;
        }

        if (count($this->middlewares) > 0) {
            foreach ($this->middlewares as $middleware) {
                /** @var Middleware $middleware */
                if ($middleware->process($call) === false) {
                    $call = $middleware->getCall();
                    $hasBeenRerouted = true;
                    break;
                }
            }
        }

        $execution = new Execution($call, $parameter);
        if ($hasBeenRerouted) {
            $execution = $execution->withReroutedPath($request->getPath());
        }
        return $execution;
    }


    /**
     * @param Request $request
     * @return Route
     * @throws RouterException
     */
    public function findRoute(Request $request)
    {
        if (count($this->getRoutes()) == 0) {
            throw new RouterException("No route available");
        }
        foreach ($this->routes as $key => $route) {
            /** @var Route $route */
            if (($params = $this->matchesRoute($route, $request)) !== false) {
                $route->setParameter($params);
                return $route;
            }
        }
        throw new RouterException("Non of the routes matches uri");
    }

    /**
     * @param Route $route
     * @param Request $request
     * @return bool
     */
    public function matchesRoute(Route $route, Request $request)
    {
        $httpMethodPattern = self::buildHttpMethod($route->getHttpMethod());
        if (preg_match($httpMethodPattern, $request->getMethod(), $httpMethodParams) === 1) {
            $uriPattern = self::buildUri($this->basePath . $route->getUri());
            $uri = explode('?', $request->getPath())[0];
            if (preg_match($uriPattern, $uri, $uriParams) === 1) {
                array_shift($uriParams);
                return $uriParams;
            }
        }
        return false;
    }

    /**
     * @param $input
     * @return string
     */
    public static function buildUri($input)
    {
        $regularExpression = str_replace(self::$uriSearch, self::$uriReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }

    /**
     * @param $input
     * @return string
     */
    public static function buildHttpMethod($input)
    {
        $regularExpression = str_replace(self::$httpMethodSearch, self::$httpMethodReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }

    /**
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }


    /**
     * @param Call $fallback
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }


    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * @param array $routes
     */
    public function addRoutes(array $routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }
}