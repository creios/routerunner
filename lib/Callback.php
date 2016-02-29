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
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

}