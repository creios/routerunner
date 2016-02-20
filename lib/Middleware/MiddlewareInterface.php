<?php

namespace TimTegeler\Routerunner\Middleware;

/**
 * Interface MiddlewareInterface
 * @package TimTegeler\Routerunner\Middleware
 */
interface MiddlewareInterface
{

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller);

    /**
     * @param Callback $callable
     */
    public function setCallback($callable);

    /**
     * @return Callback
     */
    public function getCallback();

}