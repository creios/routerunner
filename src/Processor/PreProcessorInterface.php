<?php

namespace TimTegeler\Routerunner\Processor;

use Psr\Http\Message\ServerRequestInterface;
use TimTegeler\Routerunner\Controller\ControllerInterface;

/**
 * Interface PreProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
 */
interface PreProcessorInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param ControllerInterface $controller
     * @return ServerRequestInterface
     */
    public function process(ServerRequestInterface $request, ControllerInterface $controller);
}