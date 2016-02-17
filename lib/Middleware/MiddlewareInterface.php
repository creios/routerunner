<?php

namespace TimTegeler\Routerunner\Middleware;

interface MiddlewareInterface
{

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller);

    public function setCallback($callable);

    public function getCallback();

}