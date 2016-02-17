<?php

namespace TimTegeler\Routerunner;

use ReflectionClass;
use ReflectionMethod;
use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;

/**
 * Class Router
 * @package TimTegeler\Routerunner
 */
class Router
{

    const FALLBACK_HTTP_METHOD = "GET";
    const FALLBACK_URI = "/";
    /**
     * @var string
     */
    private static $callableNameSpace = "\\";
    /**
     * @var array
     */
    private static $controllerDependencies = array();
    /**
     * @var array
     */
    private static $middlewares = array();
    /**
     * @var string
     */
    private static $loginHttpMethod;
    /**
     * @var string
     */
    private static $loginUri;

    /**
     * @param $filename
     * @throws Exception\ParseException
     */
    public static function parse($filename)
    {
        $routes = Parser::parse($filename);
        Finder::setRoutes($routes);
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @param $callback
     * @throws Exception\ParseException
     */
    public static function route($httpMethod, $uri, $callback)
    {
        $routeFormat = "%s %s %s";
        $route = sprintf($routeFormat, $httpMethod, $uri, $callback);
        Finder::addRoute(Parser::createRoute($route));
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws RouterException
     */
    public static function execute($httpMethod, $uri)
    {
        $route = self::findRoute($httpMethod, $uri);
        $callback = $route->getCallback();
        $method = $callback->getMethod();

        $controller = self::constructController(self::$callableNameSpace . "\\" . $callback->getController());

        foreach (self::$middlewares as $middleware) {
            /** @var Middleware $middleware */
            if ($middleware->process($controller) == false) {
                $callable = $middleware->getCallback();
                $method = $callable->getMethod();
                $controller = self::constructController(self::$callableNameSpace . "\\" . $callable->getController());
                break;
            }
        }

        if (method_exists($controller, $method)) {
            if (is_array($route->getParameter())) {
                return $controller->$method($route->getParameter());
            } else {
                return $controller->$method();
            }
        }

        throw new RouterException("Route is not callable");
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return Route
     * @throws RouterException
     */
    private static function findRoute($httpMethod, $uri)
    {
        try {
            $route = Finder::findRoute($httpMethod, $uri);
        } catch (RouterException $e) {
            $route = Finder::findRoute(self::FALLBACK_HTTP_METHOD, self::FALLBACK_URI);
        }
        return $route;
    }

    /**
     * @param $class
     * @return object
     */
    private static function constructController($class)
    {
        if (class_exists($class)) {
            $refMethod = new ReflectionMethod($class, '__construct');
            $params = $refMethod->getParameters();

            $re_args = array();

            foreach ($params as $key => $param) {
                if ($param->isPassedByReference()) {
                    $re_args[$key] = &self::$controllerDependencies[$key];
                } else {
                    $re_args[$key] = self::$controllerDependencies[$key];
                }
            }

            $refClass = new ReflectionClass($class);
            $controller = $refClass->newInstanceArgs($re_args);
            return $controller;
        }
    }

    /**
     * @param Middleware $middleware
     */
    public static function registerMiddleware(Middleware $middleware)
    {
        self::$middlewares[] = $middleware;
    }

    /**
     * @param $callableNameSpace
     */
    public static function setCallableNameSpace($callableNameSpace)
    {
        self::$callableNameSpace = $callableNameSpace;
    }

    /**
     * @param array $controllerDependencies
     */
    public static function setControllerDependencies(array $controllerDependencies)
    {
        self::$controllerDependencies = $controllerDependencies;
    }

    /**
     * @param string $loginHttpMethod
     * @param string $loginUri
     */
    public static function setLoginFallback($loginHttpMethod, $loginUri)
    {
        self::$loginHttpMethod = $loginHttpMethod;
        self::$loginUri = $loginUri;
    }

    /**
     * @param $filename
     */
    public static function setCacheFile($filename)
    {
        Cache::setFile($filename);
    }

    public static function activateCaching()
    {
        Parser::setCaching(True);
    }

    public static function deactivateCaching()
    {
        Parser::setCaching(False);
    }

}