<?php

namespace TimTegeler\Routerunner\Util;

/**
 * Class Config
 * @package TimTegeler\Routerunner\Util
 */
class Config
{

    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var Call
     */
    private $fallBack;
    /**
     * @var string
     */
    private $baseNamespace;

    /**
     * Config constructor.
     * @param array $routes
     * @param Call $fallBack
     * @param string $baseNamespace
     */
    public function __construct(array $routes, Call $fallBack, $baseNamespace)
    {
        $this->routes = $routes;
        $this->fallBack = $fallBack;
        $this->baseNamespace = $baseNamespace;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return Call
     */
    public function getFallBack()
    {
        return $this->fallBack;
    }

    /**
     * @return string
     */
    public function getBaseNamespace()
    {
        return $this->baseNamespace;
    }
    
}