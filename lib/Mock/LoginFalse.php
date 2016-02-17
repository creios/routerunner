<?php

namespace TimTegeler\Routerunner\Mock;

use TimTegeler\Routerunner\Middleware\Middleware;

class LoginFalse extends Middleware
{
    public function process($controller)
    {
        return false;
    }
}