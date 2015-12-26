<?php

namespace TimTegeler\Routerunner;

/**
 * Class Pattern
 * @package TimTegeler\Routerunner
 */
class Pattern
{

    private static $httpMethodSearch = array("*");
    private static $httpMethodReplace = array("GET|POST");
    private static $uriSearch = array('/', "[string]", "[numeric]");
    private static $uriReplace = array('\/', '(\w+)', '(\d+)');

    /**
     * @param $input
     * @return string
     */
    public static function buildUri($input)
    {

        $regularExpression = str_replace(self::$uriSearch, self::$uriReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }

    public static function buildHttpMethod($input)
    {
        $regularExpression = str_replace(self::$httpMethodSearch, self::$httpMethodReplace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }
}