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
        return '/^' . str_replace(['/', self::STRING, self::NUMERIC], ['\/', self::STRING_REGEXP, self::NUMERIC_REGEXP], $input) . '$/';
    }
}