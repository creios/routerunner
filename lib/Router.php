<?php

namespace TimTegeler\Routerunner;

use ReflectionClass;
use ReflectionMethod;
use TimTegeler\Routerunner\Exception\RouterException;

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
    private static $controllerDependencies = array();
    /** @var AuthorizerInterface */
    private static $authorizer;

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
        try {
            $route = Finder::findRoute($httpMethod, $uri);
        } catch (RouterException $e) {
            $route = Finder::findRoute(self::FALLBACK_HTTP_METHOD, self::FALLBACK_URI);
        }

        list($class, $method) = self::generateCallable($route);

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

            try {
                self::authorize($controller);
            } catch (\Exception $e) {
                //TODO
            }

            if ($refClass->hasMethod($method)) {
                if (is_array($route->getParameter())) {
                    return $controller->$method($route->getParameter());
                } else {
                    return $controller->$method();
                }
            }
        }
        throw new RouterException("Route is not callable");
    }

    /**
     * @param Route $route
     * @return array
     */
    private static function generateCallable(Route $route)
    {
        return explode(self::SEPERATOR_OF_CLASS_AND_METHOD, self::$callableNameSpace . "\\" . $route->getCallable());
    }

    /**
     * @param $controller
     */
    private static function authorize($controller)
    {
        if (self::$authorizer != null) {
            self::$authorizer->verify($controller);
        }
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
     * @param AuthorizerInterface $authorizer
     */
    public static function setAuthorizer(AuthorizerInterface $authorizer)
    {
        self::$authorizer = $authorizer;
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