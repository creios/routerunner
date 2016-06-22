<?php

namespace TimTegeler\Routerunner;

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;
use TimTegeler\Routerunner\Exception\RouterException;
use TimTegeler\Routerunner\Middleware\Middleware;
use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;
use TimTegeler\Routerunner\Util\Parser;
use TimTegeler\Routerunner\Util\Router;

/**
 * Class Routerunner
 * @package TimTegeler\Routerunner
 */
class Routerunner
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
        $this->router = new Router($container);
        $this->parser = new Parser();
        $config = $this->parser->parse($configFilePath);
        $this->router->setFallback($config->getFallBack());
        $this->router->getFinder()->addRoutes($config->getRoutes());
        $this->router->getFinder()->setBasePath($config->getBasePath());
    }

    /**
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws RouterException
     */
    public function execute($httpMethod, $uri)
    {
        return $this->router->execute($httpMethod, $uri);
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
        $this->router->setPostProcessor($postProcessor);
    }

    /**
     * @param $enable
     */
    public function setCaching($enable)
    {
        $this->parser->setCaching($enable);
    }

}