<?php

namespace TimTegeler\Routerunner\Mock;

use TimTegeler\Routerunner\Guard\Guard;

class LoginFalse extends Guard
{
    public function process($controller)
    {
        return false;
    }
}