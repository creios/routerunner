<?php

namespace TimTegeler\Routerunner;

/**
 * Class Route
 * @package TimTegeler\Routerunner
 */
class Route
{

    /**
     * @var string
     */
    private $httpMethod;
    /**
     * @var string
     */
    private $uri;
    /**
     * @var Callback
     */
    private $callback;
    /**
     * @var string
     */
    private $parameter;

    /**
     * Route constructor.
     * @param $httpMethod
     * @param $uri
     * @param Callback $callback
     */
    public function __construct($httpMethod, $uri, Callback $callback)
    {
        $this->httpMethod = $httpMethod;
        $this->uri = $uri;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

}