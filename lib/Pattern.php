<?php

namespace TimTegeler\Routerunner;

/**
 * Class Pattern
 * @package TimTegeler\Routerunner
 */
class Pattern
{

    const HTTP_METHOD_SEARCH = array("*");
    const HTTP_METHOD_REPLACE = array("GET|POST");
    const URI_SEARCH = array('/', "[string]", "[numeric]");
    const URI_REPLACE = array('\/', '(\w+)', '(\d+)');

    /**
     * @param $input
     * @return string
     */
    public static function buildUri($input)
    {

        $regularExpression = str_replace(self::URI_SEARCH, self::URI_REPLACE, $input);
        return sprintf('/^%s$/', $regularExpression);
    }

    public static function buildHttpMethod($input)
    {
        $regularExpression = str_replace(self::HTTP_METHOD_SEARCH, self::HTTP_METHOD_REPLACE, $input);
        return sprintf('/^%s$/', $regularExpression);
    }
}