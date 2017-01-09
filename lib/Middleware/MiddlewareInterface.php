<?php

namespace TimTegeler\Routerunner\Middleware;

use TimTegeler\Routerunner\Components\Call;

/**
 * Interface MiddlewareInterface
 * @package TimTegeler\Routerunner\Middleware
 */
interface MiddlewareInterface
{

    /**
     * MiddlewareInterface constructor.
     * @param $controllerName
     * @param $methodName
     */
    public function __construct($controllerName, $methodName);

    /**
     * @param $call
     * @return bool
     */
    public function process($call);

    /**
     * @return Call
     */
    public function getCall();

}