<?php

namespace TimTegeler\Routerunner\Mock;


use TimTegeler\Routerunner\Controller\RestControllerInterface;

class User implements RestControllerInterface
{

    /**
     * @param string $uri
     */
    public function setReroutedUri($uri)
    {
    }

    /**
     * @return string
     */
    public function create()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function delete(int $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @return string
     */
    public function list()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function retrieve(int $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function update(int $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }
}