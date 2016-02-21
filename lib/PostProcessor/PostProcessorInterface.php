<?php

namespace TimTegeler\Routerunner\PostProcessor;

/**
 * Interface PostProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
 */
interface PostProcessorInterface
{

    /**
     * @param $output
     * @return mixed
     */
    public function process($output);

}