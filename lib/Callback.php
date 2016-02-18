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
     * @param $controller
     * @param $method
     */
    public function __construct($controller, $method)
    {
        $this->setController($controller);
        $this->method = $method;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return string
     */
    private static function removeLeadingString($haystack, $needle)
    {
        if (mb_substr($haystack, 0, mb_strlen($needle)) == $needle) {
            return mb_substr($haystack, mb_strlen($needle));
        }

        return $haystack;
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
        $this->controller = self::removeLeadingString($controller, Router::getCallableNameSpace()."\\");
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