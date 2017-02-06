<?php

namespace TimTegeler\Routerunner\Processor;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface PostProcessorInterface
 * @package TimTegeler\Routerunner\Processor
 */
interface PostProcessorInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param mixed $output
     * @return mixed
     */
    public function process(ServerRequestInterface $request, $output);

}