<?php

namespace TimTegeler\Routerunner\Mock;

/**
 * Class Index
 * @package TimTegeler\Routerunner\Mock
 */
class Index
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
}