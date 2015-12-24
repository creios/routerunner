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
    private $pattern;
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
     * @param $pattern
     * @param $callable
     */
    public function __construct($httpMethod, $pattern, $callable)
    {
        $this->httpMethod = $httpMethod;
        $this->pattern = $pattern;
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
    public function getPattern()
    {
        return $this->pattern;
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