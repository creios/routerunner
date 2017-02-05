<?php

namespace TimTegeler\Routerunner\PostProcessor;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface PostProcessorInterface
 * @package TimTegeler\Routerunner\PostProcessor
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