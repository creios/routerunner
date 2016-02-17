<?php

namespace TimTegeler\Routerunner;

use ReflectionClass;
use ReflectionMethod;
use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Guard\Guard;

/**
 * Class Router
 * @package TimTegeler\Routerunner
 */
class Router
{

    const SEPERATOR_OF_CLASS_AND_METHOD = "->";
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
    private static $guards = array();
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

        foreach (self::$guards as $guard) {
            /** @var Guard $guard */
            if ($guard->process($controller) == false) {
                $callable = $guard->getCallback();
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
     * @param Guard $guard
     */
    public static function registerGuard(Guard $guard)
    {
        self::$guards[] = $guard;
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