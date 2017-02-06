<?php

namespace TimTegeler\Routerunner\Processor;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface PreProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
 */
interface PreProcessorInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function process(ServerRequestInterface $request);
}