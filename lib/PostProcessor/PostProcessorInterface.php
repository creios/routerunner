<?php

namespace TimTegeler\Routerunner\PostProcessor;

interface PostProcessorInterface
{

    /**
     * @param $controller
     * @return mixed
     */
    public function process($controller);

}