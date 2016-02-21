<?php

namespace TimTegeler\Routerunner\PostProcessor;

/**
 * Interface PostProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
 */
interface PostProcessorInterface
{

    /**
     * @param $controller
     * @param $return
     * @return mixed
     */
    public function process($controller, $return);

}