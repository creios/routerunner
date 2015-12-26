<?php

namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Exception\RouterException;

/**
 * Class Finder
 * @package TimTegeler\Routerunner
 */
class Finder
{

    /**
     * @var array
     */
    private static $routes = array();

    /**
     * @param $httpMethod
     * @param $uri
     * @return Route
     * @throws RouterException
     */
    public static function findRoute($httpMethod, $uri)
    {
        foreach (self::$routes as $key => $route) {
            /** @var Route $route */
            if (($params = self::matchesRoute($route, $httpMethod, $uri)) !== false) {
                $route->setParameter($params);
                return $route;
            }
        }
        throw new RouterException("Non of the routes matches uri");
    }

    /**
     * @param Route $route
     * @param $httpMethod
     * @param $uri
     * @return bool
     */
    public static function matchesRoute(Route $route, $httpMethod, $uri)
    {
        $httpMethodPattern = Pattern::buildHttpMethod($route->getHttpMethod());
        if(preg_match($httpMethodPattern, $httpMethod, $params2) === 1){
            $pattern = Pattern::buildUri($route->getUri());
            if (preg_match($pattern, $uri, $params) === 1) {
                array_shift($params);
                return $params;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * @param array $routes
     */
    public static function setRoutes(array $routes)
    {
        self::$routes = $routes;
    }

    /**
     * @param Route $route
     */
    public static function addRoute(Route $route)
    {
        self::$routes[] = $route;
    }

    /**
     *
     */
    public static function resetRoutes()
    {
        self::$routes = array();
    }

}