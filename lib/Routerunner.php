<?php

namespace TimTegeler\Routerunner;

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\Response;
use Interop\Container\ContainerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TimTegeler\Routerunner\Components\Dispatcher;
use TimTegeler\Routerunner\Components\Execution;
use TimTegeler\Routerunner\Components\Parser;
use TimTegeler\Routerunner\Components\Request;
use TimTegeler\Routerunner\Components\Router;
use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Routerunner
 * @package TimTegeler\Routerunner
 */
class Routerunner implements DelegateInterface
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
     * Routerunner constructor.
     * @param string $configFilePath
     * @param ContainerInterface $container
     * @throws Exception\ParseException
     */
    public function __construct($configFilePath, ContainerInterface $container = null)
    {
        if ($container == null) {
            $container = ContainerBuilder::buildDevContainer();
        }
        $this->parser = new Parser();
        $this->router = new Router();
        $this->dispatcher = new Dispatcher($container);
        $config = $this->parser->parse($configFilePath);
        $this->router->setFallback($config->getFallBack());
        $this->router->addRoutes($config->getRoutes());
        $this->router->setBasePath($config->getBasePath());
    }

    /**
     * @param string $method
     * @param string $path
     * @return mixed
     * @throws RouterException
     */
    public function execute($method, $path)
    {
        return $this->dispatch($this->route($method, $path));
    }

    /**
     * @param Execution $execution
     * @return mixed
     */
    public function dispatch(Execution $execution)
    {
        return $this->dispatcher->dispatch($execution);
    }

    /**
     * @param string $method
     * @param string $path
     * @return Execution
     */
    public function route($method, $path)
    {
        return $this->router->route(new Request($method, $path));
    }

    /**
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->router->registerMiddleware($middleware);
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
     * Dispatch the next available middleware and return the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        $result = $this->dispatch($this->route($request->getMethod(), $request->getRequestTarget()));
        if ($result instanceof ResponseInterface) {
            return $result;
        } else {
            return $response = (new Response())
                ->withProtocolVersion('1.1')
                ->withBody(\GuzzleHttp\Psr7\stream_for($result));
        }
    }
}