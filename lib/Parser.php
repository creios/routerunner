<?php

namespace TimTegeler\Routerunner;

use TimTegeler\Routerunner\Exception\ParseException;

/**
 * Class Parser
 * @package TimTegeler\Routerunner
 */
class Parser
{

    const HTTP_METHOD = '(GET|POST|\*)';
    const URI = '((\/[a-zA-Z0-9]+|\/\[string\]|\/\[numeric\]|\/)*)';
    const _CALLABLE = '([a-zA-Z0-9]*->[a-zA-Z0-9]*)';
    const ROUTE_FORMAT = '^%s[ \t]*%s[ \t]*%s^';
    /**
     * @var bool
     */
    private static $caching = True;

    /**
     * @param boolean $caching
     */
    public static function setCaching($caching)
    {
        self::$caching = $caching;
    }

    /**
     * @return string
     */
    private static function getRegularExpression()
    {
        return $routeRegularExpression = sprintf(self::ROUTE_FORMAT, self::HTTP_METHOD, self::URI, self::_CALLABLE);
    }

    /**
     * @param $route
     * @return Route
     * @throws ParseException
     */
    public static function createRoute($route)
    {
        $regularExpression = self::getRegularExpression();
        if (preg_match($regularExpression, $route, $parts) === 1) {
            array_shift($parts);
            //TODO BETTER REGEXP FOR URI
            return new Route($parts[0], $parts[1], $parts[3]);
        } else {
            throw new ParseException("Line doesn't matches Pattern");
        }
    }

    /**
     * @param $filename
     * @return array
     * @throws ParseException
     */
    public static function parse($filename)
    {
        if (self::$caching && Cache::useable()) {
            if (Cache::filled()) {
                $routes = Cache::read();
            } else {
                $routes = self::parseRoutes($filename);
                Cache::write($routes);
            }
        } else {
            $routes = self::parseRoutes($filename);
        }
        return $routes;
    }

    /**
     * @param $filename
     * @return array
     * @throws ParseException
     */
    private static function parseRoutes($filename)
    {
        $routes = array();
        if (file_exists($filename)) {
            if (($file = @fopen($filename, "r")) !== FALSE) {
                while (($route = fgets($file)) !== FALSE) {
                    $routes[] = self::createRoute($route);
                }
            } else {
                throw new ParseException("Error while reading config.");
            }
            fclose($file);
        } else {
            throw new ParseException("Config does not exists");
        }
        return $routes;
    }
}