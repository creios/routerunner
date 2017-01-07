<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface ControllerInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface ControllerInterface
{

    /**
     * @param string $uri
     */
    public function setReroutedUri($uri);
}