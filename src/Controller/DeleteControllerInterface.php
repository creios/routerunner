<?php

namespace TimTegeler\Routerunner\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface DeleteInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface DeleteControllerInterface extends ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return mixed
     */
    public function _delete(ServerRequestInterface $request, $id);
}