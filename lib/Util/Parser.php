<?php

namespace TimTegeler\Routerunner\Util;

use phpFastCache\CacheManager;
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
     * @var string
     */
    const NAMESPACE_REGEXP = "/^(?:\\\\|\\\\?[a-z_A-Z]\\w+(?:\\\\[a-z_A-Z]\\w+)*)$/";
    /**
     * @var string
     */
    const PATH_REGEXP = "/^((?:[a-zA-Z09-9])+\\/)+$/";
    /**
     * @var bool
     */
    private $caching = False;
    /**
     * @var string
     */
    private $controllerBaseNamespace = null;
    /**
     * @var Cache
     */
    private $cache;


    /**
     * Parser constructor.
     * @throws ParseException
     */
    public function __construct()
    {
        $this->cache = new Cache(CacheManager::Files(), 'routerunner_cache');
    }

    /**
     * @param $filename
     * @return Config
     * @throws ParseException
     */
    public function parse($filename)
    {
        if ($this->caching) {
            // caching is enabled and the cache is useable
            if ($this->cache->filled()) {
                // cache is filled
                // reading config from cache
                list($cacheTimestamp, $config) = $this->cache->read();
                // getting timestamp from file
                $configTimestamp = self::getTimestamp($filename, TRUE);
                if (self::needRecache($cacheTimestamp, $configTimestamp)) {
                    // config need recache
                    // writing config to cache
                    $this->cache->write([$configTimestamp, $config]);
                }
            } else {
                // cache is not filled
                $configTimestamp = self::getTimestamp($filename, TRUE);
                // arsing config
                $config = $this->parseConfig($filename);
                // writing config to cache
                $this->cache->write([$configTimestamp, $config]);
            }
        } else {
            // caching is disabled or cache is not useable
            // parsing config
            $config = $this->parseConfig($filename);
        }
        return $config;
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
        if (!file_exists($filename)) throw new ParseException(sprintf("File doesn't exist", $filename));
        if (!is_readable($filename)) throw new ParseException(sprintf("File isn't readable", $filename));
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
     * @return Config
     * @throws ParseException
     */
    private function parseConfig($filename)
    {

        self::fileUseable($filename);

        $config = Yaml::parse(file_get_contents($filename));

        if (isset($config['routes']) == false) {
            throw new ParseException("Config doesn't have a routes section");
        }

        if (is_array($config['routes']) == false) {
            throw new ParseException("Routes section of config is not a list");
        }

        if (isset($config['fallback']) == false) {
            throw new ParseException("Config doesn't have a fallback");
        }

        if (isset($config['baseNamespace']) == false) {
            throw new ParseException("Config doesn't have a baseNamespace");
        }

        $basePath = null;
        if (isset($config['basePath']) == true) {
            $basePath = $config['basePath'];
            if (preg_match(self::PATH_REGEXP, $basePath) == 0) {
                throw new ParseException("BasePath is not a valid path");
            }
        }

        self::validateBaseNamespace($config['baseNamespace']);

        $this->controllerBaseNamespace = rtrim($config['baseNamespace'], '\\');

        $routes = [];
        foreach ($config['routes'] as $routeParts) {
            $routes[] = $this->createRoute($routeParts[0], $routeParts[1], $routeParts[2]);
        }

        if (isset($config['rest']) == true) {

            if (is_array($config['rest']) == false) {
                throw new ParseException("Rest section of config is not a list");
            }

            foreach ($config['rest'] as $restParts) {
//                if (strlen($restParts[0]) > 5) {
//                    throw new ParseException("Please only use a combination of C,R,U,D,L");
//                }
                if (strstr($restParts[0], "C") !== false) {
                    $routes[] = $this->createCreateRoute($restParts[1], $restParts[2]);
                }
                if (strstr($restParts[0], "R") !== false) {
                    $routes[] = $this->createRetrieveRoute($restParts[1], $restParts[2]);
                }
                if (strstr($restParts[0], "U") !== false) {
                    $routes[] = $this->createUpdateRoute($restParts[1], $restParts[2]);
                }
                if (strstr($restParts[0], "D") !== false) {
                    $routes[] = $this->createDeleteRoute($restParts[1], $restParts[2]);
                }
                if (strstr($restParts[0], "L") !== false) {
                    $routes[] = $this->createListRoute($restParts[1], $restParts[2]);
                }
            }
        }

        return new Config($routes, $this->generateCall($config['fallback']), $this->controllerBaseNamespace, $basePath);
    }

    /**
     * @param $httpMethod
     * @param $url
     * @param $call
     * @return Route
     */
    public function createRoute($httpMethod, $url, $call)
    {
        return new Route($httpMethod, $url, $this->generateCall($call));
    }

    /**
     * @param $path
     * @param $controller
     * @return Route
     */
    private function createCreateRoute($path, $controller)
    {
        return $this->createRoute("POST", $path, $controller . self::SEPARATOR_OF_CLASS_AND_METHOD . "create");
    }

    /**
     * @param $path
     * @param $controller
     * @return Route
     */
    private function createRetrieveRoute($path, $controller)
    {
        return $this->createRoute("GET", $path . "/(numeric)", $controller . self::SEPARATOR_OF_CLASS_AND_METHOD . "retrieve");
    }

    /**
     * @param $path
     * @param $controller
     * @return Route
     */
    private function createUpdateRoute($path, $controller)
    {
        return $this->createRoute("PUT", $path . "/(numeric)", $controller . self::SEPARATOR_OF_CLASS_AND_METHOD . "update");
    }

    /**
     * @param $path
     * @param $controller
     * @return Route
     */
    private function createDeleteRoute($path, $controller)
    {
        return $this->createRoute("DELETE", $path . "/(numeric)", $controller . self::SEPARATOR_OF_CLASS_AND_METHOD . "delete");
    }

    /**
     * @param $path
     * @param $controller
     * @return Route
     */
    private function createListRoute($path, $controller)
    {
        return $this->createRoute("GET", $path, $controller . self::SEPARATOR_OF_CLASS_AND_METHOD . "list");
    }

    /**
     * @param $callable
     * @return Call
     */
    public function generateCall($callable)
    {
        list($controller, $method) = explode(self::SEPARATOR_OF_CLASS_AND_METHOD, $this->controllerBaseNamespace . '\\' . $callable);
        return new Call($controller, $method);
    }

    /**
     * @param $baseNamespace
     * @throws ParseException
     */
    private static function validateBaseNamespace($baseNamespace)
    {
        if (preg_match(self::NAMESPACE_REGEXP, $baseNamespace) == 0) {
            throw new ParseException("BaseNamespace is not a valid namespace.");
        }
    }

    /**
     * @param $enable
     */
    public function setCaching($enable)
    {
        $this->caching = $enable;
    }

}