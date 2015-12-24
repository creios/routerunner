<?php

namespace TimTegeler\Routerunner;

/**
 * Class Pattern
 * @package TimTegeler\Routerunner
 */
class Pattern
{

    /**
     *
     */
    const STRING = "[string]";

    /**
     *
     */
    const NUMERIC = "[numeric]";

    /**
     *
     */
    const STRING_REGEXP = '(\w+)';

    /**
     *
     */
    const NUMERIC_REGEXP = '(\d+)';

    /**
     * @param $input
     * @return string
     */
    public static function build($input)
    {
        $search = array('/', self::STRING, self::NUMERIC);
        $replace = array('\/', self::STRING_REGEXP, self::NUMERIC_REGEXP);
        $regularExpression = str_replace($search, $replace, $input);
        return sprintf('/^%s$/', $regularExpression);
    }
}