<?php

namespace TimTegeler\Routerunner\Middleware;

use TimTegeler\Routerunner\Call;

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
     * @return Call
     */
    public function getCall();

}