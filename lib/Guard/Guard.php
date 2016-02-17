<?php

namespace TimTegeler\Routerunner\Guard;

abstract class Guard implements GuardInterface
{

    protected $callable;

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        return true;
    }

    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    public function getCallable()
    {
        return $this->callable;
    }
}