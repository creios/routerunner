<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface ListInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface ListControllerInterface extends ControllerInterface
{
    /**
     * @return mixed
     */
    public function _list();
}