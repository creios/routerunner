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
     * @var string
     */
    private $basePath;

    /**
     * Config constructor.
     * @param array $routes
     * @param Call $fallBack
     * @param string $baseNamespace
     * @param string $basePath
     */
    public function __construct(array $routes, Call $fallBack, $baseNamespace, $basePath)
    {
        $this->routes = $routes;
        $this->fallBack = $fallBack;
        $this->baseNamespace = $baseNamespace;
        $this->basePath = $basePath;
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

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
}