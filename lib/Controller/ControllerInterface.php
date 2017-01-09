<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface ControllerInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface ControllerInterface
{

    /**
     * @param string $path
     */
    public function setReroutedPath($path);
}