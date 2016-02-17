<?php

/**
 * Interface AuthorizerInterface
 */
interface AuthorizerInterface{


    /**
     * @param $controller
     * @return mixed
     */
    public function verify($controller);

}