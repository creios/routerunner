<?php

namespace TimTegeler\Routerunner;

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
    const HTTP_METHOD = '(?<httpMethod>GET|POST|\*)';
    /**
     * @var string
     */
    const URI = '(?<url>(\/[a-zA-Z0-9]+|\/\[string\]|\/\[numeric\]|\/)*(#[a-zA-Z0-9]+)?)';
    /**
     * @var string
     */
    const _CALLABLE = '(?<callable>([a-zA-Z]*\\\\)*[a-zA-Z]+[_a-zA-Z0-9]*->[_a-zA-Z]+[_a-zA-Z0-9]*)';
    /**
     * @var string
     */
    const ROUTE_FORMAT = '^%s[ \t]*%s[ \t]*%s^';
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
     */
    public function __construct()
    {
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
        $routes = [];

        self::fileUseable($filename);

        if (($file = @fopen($filename, "r")) !== FALSE) {
            while (($route = fgets($file)) !== FALSE) {
                if (trim($route) != "") {
                    $routes[] = self::createRoute($route);
                }
            }
        } else {
            throw new ParseException(sprintf("Error while reading file (%s).", $filename));
        }
        fclose($file);

        return $routes;
    }

    /**
     * @param $route
     * @return Route
     * @throws ParseException
     */
    public function createRoute($route)
    {
        $regularExpression = self::getRegularExpression();
        if (preg_match($regularExpression, $route, $parts) === 1) {
            array_shift($parts);
            list($controller, $method) = $this->generateCallback($parts['callable']);
            return new Route($parts['httpMethod'], $parts['url'], new Callback($controller, $method));
        } else {
            throw new ParseException("Line doesn't matches Pattern");
        }
    }

    /**
     * @return string
     */
    private static function getRegularExpression()
    {
        return sprintf(self::ROUTE_FORMAT, self::HTTP_METHOD, self::URI, self::_CALLABLE);
    }

    /**
     * @param $callable
     * @return array
     */
    private function generateCallback($callable)
    {
        return explode(self::SEPARATOR_OF_CLASS_AND_METHOD, $this->controllerRootNameSpace . '\\' . $callable);
    }

    /**
     * @param string $controllerRootNameSpace
     */
    public function setControllerRootNameSpace($controllerRootNameSpace)
    {
        $this->controllerRootNameSpace = $controllerRootNameSpace;
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