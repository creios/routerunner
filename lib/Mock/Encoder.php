<?php

namespace TimTegeler\Routerunner\Mock;

use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Encoder
 */
class Encoder implements PostProcessorInterface
{

    /**
     * @param $return
     * @return string
     */
    public function process($return)
    {
        return json_encode($return);
    }
}