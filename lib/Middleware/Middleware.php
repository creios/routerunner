<?php

namespace TimTegeler\Routerunner\Middleware;

use TimTegeler\Routerunner\Call;

/**
 * Class Middleware
 * @package TimTegeler\Routerunner\Middleware
 */
abstract class Middleware implements MiddlewareInterface
{

    /**
     * @var string
     */
    protected $call;

    /**
     * Middleware constructor.
     * @param $controller
     * @param $method
     */
    public function __construct($controller, $method)
    {
        $this->call = new Call($controller, $method);
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
     * @return Call
     */
    public function getCall()
    {
        return $this->call;
    }
}