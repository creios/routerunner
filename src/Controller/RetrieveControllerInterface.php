<?php

namespace TimTegeler\Routerunner\Controller;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RetrieveInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface RetrieveControllerInterface extends ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param int $id
     * @return mixed
     */
    public function _retrieve(ServerRequestInterface $request, $id);
}