<?php

namespace TimTegeler\Routerunner\Guard;

interface GuardInterface
{

    /**
     * @param $controller
     * @return bool
     */
    public function process($controller);

    public function setCallable($callable);

    public function getCallable();

}