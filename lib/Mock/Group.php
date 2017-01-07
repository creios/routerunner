<?php

namespace TimTegeler\Routerunner\Mock;


use TimTegeler\Routerunner\Controller\RetrieveControllerInterface;

class Group implements RetrieveControllerInterface
{

    /**
     * @param string $uri
     */
    public function setReroutedUri($uri)
    {
    }

    /**
     * @param int $id
     * @return string
     */
    public function retrieve(int $id)
    {
        return __CLASS__ . "->" . __FUNCTION__;
    }
}