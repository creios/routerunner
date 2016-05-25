<?php

namespace TimTegeler\Routerunner;

use Symfony\Component\Yaml\Yaml;
use TimTegeler\Routerunner\Exception\ParseException;

/**
 * Class Parser
 * @package TimTegeler\Routerunner
 */
class Parser
{

    /**
     * @var string
     */
    const SEPARATOR_OF_CLASS_AND_METHOD = "->";
    /**
     * @var bool
     */
    private $caching = False;
    /**
     * @var string
     */
    private $controllerRootNameSpace = "";
    /**
     * @var Cache
     */
    private $cache;

    /**
     * Parser constructor.
     * @param $controllerRootNameSpace
     */
    public function __construct($controllerRootNameSpace)
    {
        $this->controllerRootNameSpace = rtrim($controllerRootNameSpace, '\\');
        $this->cache = new Cache();
    }

    /**
     * @param $filename
     * @return array
     * @throws ParseException
     */
    public function parse($filename)
    {
        if ($this->caching && $this->cache->useable()) {
            // caching is enabled and the cache is useable
            if ($this->cache->filled()) {
                // cache is filled
                // reading routes from cache
                list($cacheTimestamp, $routes) = $this->cache->read();
                // getting timestamp from file
                $routesTimestamp = self::getTimestamp($filename, TRUE);
                if (self::needRecache($cacheTimestamp, $routesTimestamp)) {
                    // routes need recache
                    // writing routes to cache
                    $this->cache->write([$routesTimestamp, $routes]);
                }
            } else {
                // cache is not filled
                $routesTimestamp = self::getTimestamp($filename, TRUE);
                // arsing routes
                $routes = $this->parseRoutes($filename);
                // writing routes to cache
                $this->cache->write([$routesTimestamp, $routes]);
            }
        } else {
            // caching is disabled or cache is not useable
            // parsing routes
            $routes = $this->parseRoutes($filename);
        }
        return $routes;
    }

    /**
     * @param $filename
     * @param bool $clearCache
     * @return int
     * @throws ParseException
     */
    private static function getTimestamp($filename, $clearCache = FALSE)
    {
        self::fileUseable($filename, $clearCache);
        return filemtime($filename);
    }

    /**
     * @param $filename
     * @param bool $clearCache
     * @return bool
     * @throws ParseException
     */
    private static function fileUseable($filename, $clearCache = FALSE)
    {
        if ($clearCache) clearstatcache(True, $filename);
        if (!file_exists($filename)) throw new ParseException(sprintf("File (%s) doesn't exist.", $filename));
        if (!is_readable($filename)) throw new ParseException(sprintf("File (%s) isn't readable.", $filename));
        return true;
    }

    /**
     * @param $cacheTimestamp
     * @param $routesTimestamp
     * @return bool
     */
    private function needRecache($cacheTimestamp, $routesTimestamp)
    {
        return $cacheTimestamp !== $routesTimestamp;
    }

    /**
     * @param $filename
     * @return array
     * @throws ParseException
     */
    private function parseRoutes($filename)
    {

        self::fileUseable($filename);

        $config = Yaml::parse(file_get_contents($filename));
        $routes = [];
        foreach ($config['routes'] as $routeParts) {
            $routes[] = $this->createRoute($routeParts[0], $routeParts[1], $routeParts[2]);
        }
        return $routes;
    }

    /**
     * @param $httpMethod
     * @param $url
     * @param $call
     * @return Route
     */
    public function createRoute($httpMethod, $url, $call)
    {
        list($controller, $method) = $this->generateCall($call);
        return new Route($httpMethod, $url, new Call($controller, $method));
    }

    /**
     * @param $callable
     * @return array
     */
    private function generateCall($callable)
    {
        return explode(self::SEPARATOR_OF_CLASS_AND_METHOD, $this->controllerRootNameSpace . '\\' . $callable);
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param $enable
     */
    public function setCaching($enable)
    {
        $this->caching = $enable;
    }
}