<?php

namespace TimTegeler\Routerunner;

/**
 * Class Call
 * @package TimTegeler\Routerunner
 */
class Call
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
     * Call constructor.
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