<?php

namespace TimTegeler\Routerunner\Mock;

use TimTegeler\Routerunner\Controller\ControllerInterface;

/**
 * Class Index
 * @package TimTegeler\Routerunner\Mock
 */
class Index implements ControllerInterface
{

    /**
     * Index constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public static function get()
    {
        return "index->get";
    }

    /**
     * @return string
     */
    public static function post()
    {
        return "index->post";
    }

    /**
     * @return string
     */
    public static function login()
    {
        return "index->login";
    }

    public static function api()
    {
        return ['index' => 'login'];
    }

    /**
     * @param string $uri
     */
    public function setReroutedUri($uri)
    {
    }

}