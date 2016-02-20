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
     * @return mixed
     */
    public function process($controller);

}