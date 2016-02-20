<?php

namespace TimTegeler\Routerunner\Mock;

use TimTegeler\Routerunner\Middleware\Middleware;

/**
 * Class LoginFalse
 * @package TimTegeler\Routerunner\Mock
 */
class LoginFalse extends Middleware
{

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return false;
    }
}