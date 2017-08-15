<?php

namespace TimTegeler\Routerunner\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use TimTegeler\Routerunner\Components\Call;

/**
 * Interface MiddlewareInterface
 * @package TimTegeler\Routerunner\Middleware
 */
interface MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param Call $call
     * @return bool
     */
    public function process(ServerRequestInterface $request, $call);

    /**
     * @return Call
     */
    public function getCall();

}