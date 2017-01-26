<?php

namespace TimTegeler\Routerunner\Middleware;

use TimTegeler\Routerunner\Components\Call;

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
     * @param string $controllerName
     * @param string $methodName
     */
    public function __construct($controllerName, $methodName)
    {
        $this->call = new Call($controllerName, $methodName);
    }

    /**
     * @param Call $call
     * @return bool
     */
    public function process($call)
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