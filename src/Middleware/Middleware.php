<?php

namespace TimTegeler\Routerunner\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use TimTegeler\Routerunner\Components\Call;

/**
 * Class Middleware
 * @package TimTegeler\Routerunner\Middleware
 */
abstract class Middleware implements MiddlewareInterface
{

    /**
     * @var Call
     */
    protected $call;

    /**
     * Middleware constructor.
     * @param Call $call
     */
    public function __construct(Call $call)
    {
        $this->call = $call;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @param Call $call
     * @return bool
     */
    public function process(ServerRequestInterface $serverRequest, $call)
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