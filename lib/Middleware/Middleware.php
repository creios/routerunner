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
    protected $callback;

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return true;
    }

    /**
     * @param Callback $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }
}