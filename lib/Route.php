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
     * @var Call
     */
    private $call;
    /**
     * @var array
     */
    private $parameter;

    /**
     * Route constructor.
     * @param $httpMethod
     * @param $uri
     * @param \TimTegeler\Routerunner\Call $call
     */
    public function __construct($httpMethod, $uri, $call)
    {
        $this->httpMethod = $httpMethod;
        $this->uri = $uri;
        $this->call = $call;
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
     * @return \TimTegeler\Routerunner\Call
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @return array
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