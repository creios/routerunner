<?php

namespace TimTegeler\Routerunner;

/**
 * Class Pattern
 * @package TimTegeler\Routerunner
 */
class Pattern
{

    /**
     * @var array
     */
    private static $httpMethodSearch = array("*");
    /**
     * @var array
     */
    private static $httpMethodReplace = array("GET|POST");
    /**
     * @var array
     */
    private static $uriSearch = array('/', "[string]", "[numeric]");
    /**
     * @var array
     */
    private static $uriReplace = array('\/', '(\w+)', '\d+(\.\d+)?');

    /**
     * @param $input
     * @return string
     */
    public static function buildUri($input)
    {

        $regularExpression = str_replace(self::$uriSearch, self::$uriReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }

    /**
     * @param $input
     * @return string
     */
    public static function buildHttpMethod($input)
    {
        $regularExpression = str_replace(self::$httpMethodSearch, self::$httpMethodReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }
}