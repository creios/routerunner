<?php

namespace TimTegeler\Routerunner;

/**
 * Interface AuthorizerInterface
 */
interface AuthorizerInterface
{

    /**
     * @param $controller
     * @return bool
     */
    public function verify($controller);

}