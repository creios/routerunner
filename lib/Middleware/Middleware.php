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
     * @param $callable
     */
    public function setCallback($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callable;
    }
}