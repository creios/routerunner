<?php

namespace TimTegeler\Routerunner\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface UpdateInterface
 * @package TimTegeler\Routerunner\Controller\Rest
 */
interface UpdateControllerInterface extends ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return mixed
     */
    public function _update(ServerRequestInterface $request, $id);
}