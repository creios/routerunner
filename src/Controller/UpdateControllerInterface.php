<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface UpdateInterface
 * @package TimTegeler\Routerunner\Controller\Rest
 */
interface UpdateControllerInterface extends ControllerInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function _update($id);
}