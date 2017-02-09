<?php

namespace TimTegeler\Routerunner\Controller;

/**
 * Interface RestControllerControllerInterface
 * @package TimTegeler\Routerunner\Controller
 */
interface RestControllerInterface extends
    CreateControllerInterface,
    RetrieveControllerInterface,
    UpdateControllerInterface,
    DeleteControllerInterface,
    ListControllerInterface
{

}