<?php

namespace TimTegeler\Routerunner\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface CreateInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface CreateControllerInterface extends ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function _create(ServerRequestInterface $request);
}