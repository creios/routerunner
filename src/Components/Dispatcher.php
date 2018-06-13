<?php

namespace TimTegeler\Routerunner\Components;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionMethod;
use TimTegeler\Routerunner\Controller\ControllerInterface;
use TimTegeler\Routerunner\Controller\CreateControllerInterface;
use TimTegeler\Routerunner\Controller\ListControllerInterface;
use TimTegeler\Routerunner\Exception\DispatcherException;
use TimTegeler\Routerunner\Processor\PostProcessorInterface;
use TimTegeler\Routerunner\Processor\PreProcessorInterface;

/**
 * Class Dispatcher
 * @package TimTegeler\Routerunner\Components
 */
class Dispatcher
{

    /**
     * @var PreProcessorInterface
     */
    private $preProcessor;
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
     * @throws \ReflectionException
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

                // pre processing
                if ($this->preProcessor != null) {
                    $request = $this->preProcessor->process($request, $controller);
                }

                // prepare parameters
                if ($execution->hasParameters()) {
                    $request = $request->withAttribute('parameters', $execution->getParameters());
                }
                $arguments = array_merge([$request], $execution->getParameters());

                // actual dispatch
                $refMethod = new ReflectionMethod($controllerName, $methodName);
                $return = $refMethod->invokeArgs($controller, $arguments);

                // pre processing
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
     * @param PreProcessorInterface $preProcessor
     */
    public function setPreProcessor($preProcessor)
    {
        $this->preProcessor = $preProcessor;
    }

    /**
     * @param PostProcessorInterface $postProcessor
     */
    public function setPostProcessor($postProcessor)
    {
        $this->postProcessor = $postProcessor;
    }

}
