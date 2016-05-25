<?php

namespace TimTegeler\Routerunner\Util;

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
    private $routes = [];
    /**
     * @var string
     */
    private $baseUri;

    /**
     * @param $httpMethod
     * @param $uri
     * @return Route
     * @throws RouterException
     */
    public function findRoute($httpMethod, $uri)
    {
        if (count($this->getRoutes()) == 0) {
            throw new RouterException("No route available");
        }
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
     * @param $httpMethod
     * @param $uri
     * @return bool
     */
    public function matchesRoute(Route $route, $httpMethod, $uri)
    {
        $httpMethodPattern = Pattern::buildHttpMethod($route->getHttpMethod());
        if (preg_match($httpMethodPattern, $httpMethod, $httpMethodParams) === 1) {
            $uriPattern = Pattern::buildUri($this->baseUri . $route->getUri());
            $uri = explode('?', $uri)[0];
            if (preg_match($uriPattern, $uri, $uriParams) === 1) {
                array_shift($uriParams);
                return $uriParams;
            }
        }
        return false;
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
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

}