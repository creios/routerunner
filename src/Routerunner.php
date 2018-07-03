<?php

namespace TimTegeler\Routerunner;

use DI\Container;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TimTegeler\Routerunner\Components\Dispatcher;
use TimTegeler\Routerunner\Components\Execution;
use TimTegeler\Routerunner\Components\Parser;
use TimTegeler\Routerunner\Components\Router;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\Processor\PostProcessorInterface;
use TimTegeler\Routerunner\Processor\PreProcessorInterface;

/**
 * Class Routerunner
 * @package TimTegeler\Routerunner
 */
class Routerunner implements MiddlewareInterface
{

    /**
     * @var Router
     */
    private $router;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var Container
     */
    private $container;

    /**
     * Routerunner constructor.
     * @param string $configFilePath
     * @param Container $container
     * @throws Exception\ParseException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct($configFilePath, Container $container)
    {
        $this->container = $container;
        $this->parser = $this->container->get(Parser::class);
        $this->router = $this->container->get(Router::class);
        $this->dispatcher = new Dispatcher($container);
        $config = $this->parser->parse($configFilePath);
        $this->router->setFallback($config->getFallBack());
        $this->router->addRoutes($config->getRoutes());
        $this->router->setBasePath($config->getBasePath());
    }

    /**
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->router->registerMiddleware($middleware);
    }

    /**
     * @param PreProcessorInterface $preProcessor
     */
    public function setPreProcessor(PreProcessorInterface $preProcessor)
    {
        $this->dispatcher->setPreProcessor($preProcessor);
    }

    /**
     * @param PostProcessorInterface $postProcessor
     */
    public function setPostProcessor(PostProcessorInterface $postProcessor)
    {
        $this->dispatcher->setPostProcessor($postProcessor);
    }

    /**
     * @param $enable
     */
    public function setCaching($enable)
    {
        $this->parser->setCaching($enable);
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     * @throws Exception\DispatcherException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $this->container->set(ServerRequestInterface::class, $request);
        $result = $this->dispatch($this->route($request));
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        return $response = (new Response())
            ->withProtocolVersion('1.1')
            ->withBody(\GuzzleHttp\Psr7\stream_for($result));
    }

    /**
     * @param Execution $execution
     * @return mixed
     * @throws Exception\DispatcherException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    protected function dispatch(Execution $execution)
    {
        return $this->dispatcher->dispatch($execution);
    }

    /**
     * @param ServerRequestInterface $request
     * @return Execution
     */
    protected function route(ServerRequestInterface $request)
    {
        return $this->router->route($request);
    }
}
