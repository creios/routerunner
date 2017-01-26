<?php

namespace TimTegeler\Routerunner\PostProcessor;

/**
 * Interface PostProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
 */
interface PostProcessorInterface
{

    /**
     * @param mixed $output
     * @return mixed
     */
    public function process($output);

}