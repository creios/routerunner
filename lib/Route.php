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
     * @var string
     */
    private $callable;
    /**
     * @var string
     */
    private $parameter;

    /**
     * Route constructor.
     * @param $httpMethod
     * @param $uri
     * @param $callable
     */
    public function __construct($httpMethod, $uri, $callable)
    {
        $this->httpMethod = $httpMethod;
        $this->uri = $uri;
        $this->callable = $callable;
    }

    /**
     * @return mixed
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return mixed
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