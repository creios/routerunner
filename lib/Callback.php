<?php

namespace TimTegeler\Routerunner;

/**
 * Class Callback
 * @package TimTegeler\Routerunner
 */
class Callback
{

    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $method;

    /**
     * Callback constructor.
     * @param string $controller
     * @param string $method
     */
    public function __construct($controller, $method)
    {
        $this->controller = $controller;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

}