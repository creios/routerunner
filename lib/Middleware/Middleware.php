<?php

namespace TimTegeler\Routerunner\Middleware;

/**
 * Class Middleware
 * @package TimTegeler\Routerunner\Middleware
 */
abstract class Middleware implements MiddlewareInterface
{

    /**
     * @var string
     */
    protected $callable;

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return true;
    }

    /**
     * @param Callback $callable
     */
    public function setCallback($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callable;
    }
}