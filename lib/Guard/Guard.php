<?php

namespace TimTegeler\Routerunner\Guard;

/**
 * Class Guard
 * @package TimTegeler\Routerunner\Guard
 */
abstract class Guard implements GuardInterface
{

    /**
     * @var string
     */
    protected $callable;

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return true;
    }

    /**
     * @param $callable
     */
    public function setCallback($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callable;
    }
}