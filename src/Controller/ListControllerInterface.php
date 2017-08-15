<?php

namespace TimTegeler\Routerunner\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ListInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface ListControllerInterface extends ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function _list(ServerRequestInterface $request);
}