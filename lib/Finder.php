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
    private $routes = array();

    /**
     * @param $httpMethod
     * @param $uri
     * @return Route
     * @throws RouterException
     */
    public function findRoute($httpMethod, $uri)
    {
        foreach ($this->routes as $key => $route) {
            /** @var Route $route */
            if (($params = $this->matchesRoute($route, $httpMethod, $uri)) !== false) {
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
    public function matchesRoute(Route $route, $httpMethod, $uri)
    {
        $httpMethodPattern = Pattern::buildHttpMethod($route->getHttpMethod());
        if (preg_match($httpMethodPattern, $httpMethod, $params2) === 1) {
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

}