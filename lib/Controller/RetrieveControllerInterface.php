<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface RetrieveInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface RetrieveControllerInterface extends ControllerInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function retrieve(int $id);
}