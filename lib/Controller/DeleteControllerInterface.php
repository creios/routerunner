<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface DeleteInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface DeleteControllerInterface extends ControllerInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}