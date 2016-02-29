<?php

namespace TimTegeler\Routerunner\Middleware;

/**
 * Interface MiddlewareInterface
 * @package TimTegeler\Routerunner\Middleware
 */
interface MiddlewareInterface
{

    /**
     * MiddlewareInterface constructor.
     * @param $controller
     * @param $method
     */
    public function __construct($controller, $method);

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller);

    /**
     * @return Callback
     */
    public function getCallback();

}