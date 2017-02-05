<?php

namespace TimTegeler\Routerunner\Components;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionMethod;
use TimTegeler\Routerunner\Controller\ControllerInterface;
use TimTegeler\Routerunner\Exception\DispatcherException;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Dispatcher
 * @package TimTegeler\Routerunner\Components
 */
class Dispatcher
{

    /**
     * @var PostProcessorInterface
     */
    private $postProcessor;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Dispatcher constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Execution $execution
     * @return mixed
     * @throws DispatcherException
     */
    public function dispatch(Execution $execution)
    {
        $controllerName = $execution->getCall()->getController();
        $methodName = $execution->getCall()->getMethod();

        if (class_exists($controllerName)) {
            $refClass = new ReflectionClass($controllerName);

            if ($refClass->hasMethod($methodName)) {
                $controller = $this->container->get($controllerName);
                /** @var ControllerInterface $controller */
                if ($execution->hasRerouted()) {
                    $controller->setReroutedPath($execution->getReroutedPath());
                }
                $request = $this->container->get(ServerRequestInterface::class);
                $refMethod = new ReflectionMethod($controllerName, $methodName);
                $return = $refMethod->invokeArgs($controller,
                    array_merge([$request], $execution->getParameters()));

                if ($this->postProcessor != null) {
                    return $this->postProcessor->process($request, $return);
                } else {
                    return $return;
                }

            } else {
                throw new DispatcherException("Method can not be found.");
            }

        } else {
            throw new DispatcherException("Controller can not be found.");
        }
    }

    /**
     * @param PostProcessorInterface $postProcessor
     */
    public function setPostProcessor($postProcessor)
    {
        $this->postProcessor = $postProcessor;
    }

}