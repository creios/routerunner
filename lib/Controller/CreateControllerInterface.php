<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface CreateInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface CreateControllerInterface extends ControllerInterface
{
    /**
     * @return mixed
     */
    public function create();
}