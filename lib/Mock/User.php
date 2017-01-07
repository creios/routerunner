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
    public function _create()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _delete($id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @return string
     */
    public function _list()
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _retrieve($id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }

    /**
     * @param int $id
     * @return string
     */
    public function _update($id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }
}