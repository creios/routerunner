<?php

namespace TimTegeler\Routerunner\Middleware;

use TimTegeler\Routerunner\Callback;

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
     * Middleware constructor.
     * @param $controller
     * @param $method
     */
    public function __construct($controller, $method)
    {
        $this->callback = new Callback($controller, $method);
    }

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return true;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }
}